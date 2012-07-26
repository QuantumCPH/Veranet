<?php

class EmployeeCallhistoryPeer extends BaseEmployeeCallhistoryPeer
{

    static public function getTotalCallDuration(Employee $employee,$country_id,PropelPDO $con = null)
    {
       if($con === null) {
          $con = Propel::getConnection(EmployeeCallhistoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
       }

       $stmt = $con->prepare('SELECT sec_to_time(sum(' . EmployeeCallhistoryPeer::CHARGED_QUANTITY . ')) FROM ' . EmployeeCallhistoryPeer::TABLE_NAME . ' Where '. EmployeeCallhistoryPeer::EMPLOYEE_ID .'='.$employee->getId().' and '. EmployeeCallhistoryPeer::COUNTRY_ID .'='.$country_id);
       $stmt->execute();
       return $stmt->fetchColumn();
    }
   
    static public function getCallDuration($call_id,PropelPDO $con = null)
    {
       if($con === null) {
          $con = Propel::getConnection(EmployeeCallhistoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
       }

       $stmt = $con->prepare('SELECT sec_to_time(' . EmployeeCallhistoryPeer::CHARGED_QUANTITY . ') FROM ' . EmployeeCallhistoryPeer::TABLE_NAME . ' Where '. EmployeeCallhistoryPeer::ID .'='.$call_id);
       $stmt->execute();
       return $stmt->fetchColumn();
    }
}
