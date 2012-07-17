<?php

class Product extends BaseProduct
{
	function __toString()
	{
		return $this->getName();
	}

         function getTotalAmount(){
            return $this->getPrice()+$this->getRegistrationFee();
        }

        function getNetBalanceGiven(){
            return $this->getInitialBalance()+$this->getBonus();
        }
}
