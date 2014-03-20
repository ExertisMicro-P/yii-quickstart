<?php
/**
 * Add facility to record certain important lifetime
 * events against records, using an Audit Trail
 * @author russellh
 *
 */
class MPActiveRecord extends CActiveRecord {



        public function _logToAuditTrail($msg) {
            $auditentry = new Audittrail();
        $auditentry->message=$msg;
            $auditentry->table_name=$this->tableName();
            $auditentry->record_id=floor($this->getPrimaryKey());
            if( Yii::app() instanceof CConsoleApplication)
                    $auditentry->username = 'console application';
            else
                    $auditentry->username= Yii::app()->user->name;

            $auditentry->save();
        } // _logToAuditTrail

	/**
	 * Save this record and add one or more entries to the Audit Trail
	 * @param mixed $message Message (string) or Array of Messages to put in the Audit Trail
	 */
	public function saveWithAuditTrail($message='', $runValidation=true, array $attributes=NULL) {
		//Yii::log(__METHOD__.': message='.print_r($message,true), 'info', 'system.webservice.ProductSupplierWS');

		$result = parent::save($runValidation, $attributes);

		if (!is_array($message))
			$messages = array($message);
		else
			$messages = $message;

		foreach($messages as $msg) {
                    $this->_logToAuditTrail($msg);
		}

		return $result;

	} // saveWithAuditTrail
}