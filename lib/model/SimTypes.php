<?php

class SimTypes extends BaseSimTypes
{
   public function __toString()
    {
      return $this->getTitle();
    }
}
