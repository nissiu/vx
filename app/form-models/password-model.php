<?php

namespace Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Password_Model extends Base_Form_Model {

	protected function template() { ?>
		<input type="password" <?= $this->attributes('v-model', 'required') ?>>
	<?php }

}
