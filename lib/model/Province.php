<?php

class Province extends BaseProvince
{
      public function __toString()
    {
      return __($this->getProvince());
    }
}
