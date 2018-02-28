<?php
/**
 * ExcelParserService file
 *
 * PHP Version 7
 *
 * @category Service
 *
 * @package  CoreBundle\Service
 */

namespace CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Liuggio\ExcelBundle\Factory as PHPExcel;
use MatchBundle\Entity\GroupMatch;
use SchoolBundle\Entity\School;
use SchoolBundle\Entity\Student;
use TeamBundle\Entity\Team;
use UserBundle\Entity\User;

/**
 * ExcelParserService class
 */
class ExcelParserService
{
    protected $em;
    protected $phpExcel;
    protected $mailHost;

    /** @var School $school */
    protected $school;
    /** @var  User $teacher */
    protected $teacher;
    /** @var  GroupMatch $group */
    protected $group;
    /** @var  Team $team */
    protected $team;

    /**
     * excelParserService constructor.
     *
     * @param EntityManager $entityManager
     * @param PHPExcel      $phpExcel
     * @param string        $mailHost
     */
    public function __construct(EntityManager $entityManager, PHPExcel $phpExcel, $mailHost)
    {
        $this->em = $entityManager;
        $this->phpExcel = $phpExcel;
        $this->mailHost = $mailHost;
    }

    /**
     * @param string $filePath
     */
    public function parseExcelFile($filePath)
    {
        $phpExcelObject = $this->phpExcel->createPHPExcelObject($filePath);
        $sheets = $this->getSheets($phpExcelObject);

        $mappedCpt = 2;
        while ($sheets['mapped']->getCell('A'.$mappedCpt)->getValue()) {
            $ids = $this->getIds($sheets['mapped'], $mappedCpt);
            $this
                ->getSetSchool($sheets['school'], $ids['school'])
                ->getSetGroup($sheets['group'], $ids['group'])
                ->createTeam($ids['team'])
                ->getSetStudent($sheets['team'], $ids['team'])
            ;
            $mappedCpt ++;
        }
        $this->em->flush();
    }

    /**
     * @param \PHPExcel $phpExcelObject
     *
     * @return array
     */
    private function getSheets($phpExcelObject)
    {
        return [
            'mapped' => $phpExcelObject->getSheetByName('ecole-classe-equipe'),
            'school' => $phpExcelObject->getSheetByName('ecole'),
            'group'  => $phpExcelObject->getSheetByName('classe'),
            'team'   => $phpExcelObject->getSheetByName('equipe'),
        ];
    }

    /**
     * @param \PHPExcel_Worksheet $sheet
     * @param integer             $cpt
     *
     * @return array
     */
    private function getIds($sheet, $cpt)
    {
        return [
            'school' => $sheet->getCell('A'.$cpt)->getValue(),
            'group'  => $sheet->getCell('B'.$cpt)->getValue(),
            'team'   => $sheet->getCell('C'.$cpt)->getValue(),
        ];
    }

    /**
     * @param string $name
     *
     * @return array
     */
    private function formatName($name)
    {
        return [
            'username'   => str_replace(' ', '_', strtolower($name)),
            'first_name' => lcfirst(explode(' ', $name)[0]),
            'last_name'  => lcfirst(explode(' ', $name)[1]),
        ];
    }

    /**
     * @param \PHPExcel_Worksheet $schoolSheet
     * @param integer             $schoolId
     */
    private function getSetSchool($schoolSheet, $schoolId)
    {
        $schoolCpt = 2;
        while ($schoolSheet->getCell('A'.$schoolCpt)->getValue()) {
            if ($schoolId == $schoolSheet->getCell('A'.$schoolCpt)->getValue()) {
                $schoolName = $schoolSheet->getCell('B'.$schoolCpt)->getValue();
                $this->school = $this->createSchool($schoolName);
            }
            $schoolCpt ++;
        }

        return $this;
    }

    /**
     * @param \PHPExcel_Worksheet $groupSheet
     * @param integer             $groupId
     *
     * @return $this
     */
    private function getSetGroup($groupSheet, $groupId)
    {
        $groupCpt = 2;
        while ($groupSheet->getCell('A'.$groupCpt)->getValue()) {
            if ($groupId == $groupSheet->getCell('A'.$groupCpt)->getValue()) {
                $teacherName = $groupSheet->getCell('B'.$groupCpt)->getValue();
                $this->teacher = $this->createTeacher($teacherName);
                $this->group = $this->createGroup($groupSheet, $groupCpt);
            }
            $groupCpt ++;
        }

        return $this;
    }

    /**
     * @param \PHPExcel_Worksheet $teamSheet
     * @param Team                $teamId
     *
     * @return GroupMatch|boolean
     */
    private function getSetStudent($teamSheet, $teamId)
    {
        $teamCpt = 2;
        while ($teamSheet->getCell('A'.$teamCpt)->getValue()) {
            if ($teamId == $teamSheet->getCell('A'.$teamCpt)->getValue()) {
                $letters = range('B', 'S');
                foreach ($letters as $key => $letter) {
                    if ($key % 3 == 0 && $key != 0) {
                        $lastName = $teamSheet->getCell($letters[$key-3].$teamCpt)->getValue();
                        $firstName = $teamSheet->getCell($letters[$key-2].$teamCpt)->getValue();
                        $roleName = $teamSheet->getCell($letters[$key-1].$teamCpt)->getValue();
                        $this->createStudent($lastName, $firstName, $roleName);
                        if (!$teamSheet->getCell($letters[$key].$teamCpt)->getValue()) {
                            break;
                        }
                    }
                }
            }
            $teamCpt ++;
        }
    }

    /**
     * @param string $name
     *
     * @return School
     */
    private function createSchool($name)
    {
        $school = new School();
        $school->setName($name);

        $this->em->persist($school);

        return $school;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    private function createTeacher($name)
    {
        $formatedTeacher = $this->formatName($name);
        $teacher = $this->em->getRepository('UserBundle:User')->findOneBy(['username' => $formatedTeacher['username']]);

        if ($teacher) return $teacher;

        $email = strtolower($formatedTeacher['first_name']).'.'.
            strtolower($formatedTeacher['last_name']).
            '@'.$this->mailHost;

        $teacher = new User();
        $teacher->setUsername($formatedTeacher['username']);
        $teacher->setFirstName($formatedTeacher['first_name']);
        $teacher->setLastName($formatedTeacher['last_name']);
        $teacher->setEmail($email);
        $teacher->setSchool($this->school);
        $teacher->setPlainPassword('teacher');

        $this->em->persist($teacher);

        return $teacher;
    }

    /**
     * @param \PHPExcel_Worksheet $groupSheet
     * @param integer             $line
     *
     * @return GroupMatch
     */
    private function createGroup($groupSheet, $line)
    {
        $groupName = $this->school->getName().' - '.
            $groupSheet->getCell('C'.$line)->getValue().' - '.
            $this->teacher->getUsername();

        $group = new GroupMatch();
        $group->setName($groupName);
        $group->setLevel($groupSheet->getCell('D'.$line)->getValue());
        $group->setTeacher($this->teacher);

        $this->em->persist($group);

        return $group;
    }

    /**
     * @param integer $teamId
     *
     * @return $this
     */
    private function createTeam($teamId)
    {
        $this->team = new Team();
        $this->team->setName('Equipe '.$teamId);
        $this->team->setGroup($this->group);

        $this->em->persist($this->team);

        return $this;
    }

    /**
     * @param string $lastName
     * @param string $firstName
     * @param string $roleName
     */
    private function createStudent($lastName, $firstName, $roleName)
    {
        $role = $this->em->getRepository('CoreBundle:Role')->findOneBy(['name' => $roleName]);

        $student = new Student();
        $student->setLastName($lastName);
        $student->setFirstName($firstName);
        $student->setRole($role);
        $student->setSchool($this->school);
        $student->setTeam($this->team);

        $this->em->persist($student);
    }
}