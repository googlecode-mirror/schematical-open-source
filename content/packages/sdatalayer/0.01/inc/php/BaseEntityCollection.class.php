<?php
/*
 * Manages collections of data objects
 */
class BaseEntityCollection
	{
		
		protected $collection = array();
		public function getIDsAsArray(){
			$arrReturn = array();
			foreach($this->collection as $objEntity){
				$arrReturn[$objEntity->getId()] = $objEntity->getId();
			}
			return $arrReturn;
		}
		public function getRandomized(){
			$arrColl = $this->collection;
			$arrReturn = array();
			while(count($arrColl) != 0){
				$i = rand(0, count($arrColl) - 1);
				$arrReturn[] = $arrColl[$i];
				array_splice($arrColl, $i, 1);
			}
			return $arrReturn;		
		}
		
		public function getItemByIndex($index){
			return $this->collection[$index];
		}
		public function addItem($item){
			array_push($this->collection,$item);
		}
		public function getCollection(){
			return $this->collection;
		}
		public function length(){
			return count($this->collection);
		}
		public function combine($nColl){
			$coll = $nColl->getCollection();
			
			foreach($coll as $mObj){
				array_push($this->collection,$mObj);
			}
			
		}
		public function contains($nObj){
			if(isset($nObj)){
				$coll = $this->getCollection();
				$bool = false;
				foreach($coll as $mObj){
					if($nObj->equals($mObj)){
						$bool =  true;
					}
				}
				return $bool;
			}else{
				return false;
			}
		}
		public function getIndexOf($nObj){
			if(isset($nObj)){
				$coll = $this->getCollection();
				$bool = false;
				for($i = 0; $i < sizeof($coll); $i++){
					if($nObj->equals($coll[$i])){
						$rIndex =  $i;
					}
				}
				return $rIndex;
			}else{
				return false;
			}
		}
		public function remove($nObj){
			if((isset($nObj)) and ($this->contains($nObj))){
				$coll = $this->getCollection();
				$bool = false;
				$nColl = array();
				for ($i = 0; $i <= (count($coll) - 1); $i++) {
					$mObj = $coll[$i];
					
					if($nObj->equals($mObj)){
						//do nothing
					}else{
						array_push($nColl, $mObj);
					}
				}
				$this->collection = $nColl;
				return $bool;
			}else{
				return false;
			}
		}
		public function removeColl($nColl){
			foreach($nColl as $nObj){
				$this->remove($nObj);
			}
		}
		public function __construct(){
			
		}
		public function save(){
			foreach($this->collection as $entity){
				LoadDriver::save($entity);
			}
		}
		public function markAllDeleted(){
			$coll = $this->collection;
			foreach($coll as $obj){
				try{
					$obj->markDeleted();
				}catch(Exception $e){
					echo "This object type does not have a markDeleted funcion";
				}
			}
		}
		public function removeByIndex($intIndex){
			unset($this->collection[$intIndex]);
		}
			
	
	}
	
?>