<?php

namespace DevBieres\Task\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DevBieresTaskUserBundle extends Bundle
{
  public function getParent() { return 'FOSUserBundle'; }

}
