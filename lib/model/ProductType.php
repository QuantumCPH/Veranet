<?php

class ProductType extends BaseProductType
{
    function __toString()
	{
		return $this->getTitle();
	}
}
