<?php

class BillingProducts extends BaseBillingProducts
{
    public function __toString(){
		return $this->getTitle();
	}
}
