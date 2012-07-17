<?php

class EnableCountry extends BaseEnableCountry
{
    public function __toString(){
	return $this->getName();
	}
}
