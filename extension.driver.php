<?php

	class Extension_SystemIdField extends Extension
	{
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/

		public function uninstall()
		{
			return Symphony::Database()
				->drop('tbl_fields_systemid')
				->ifExists()
				->execute()
				->success();
		}

		public function install()
		{
			return Symphony::Database()
				->create('tbl_fields_systemid')
				->ifNotExists()
				->charset('utf8')
				->collate('utf8_unicode_ci')
				->fields([
					'id' => [
						'type' => 'int(11)',
						'auto' => true,
					],
					'field_id' => 'int(11)',
				])
				->keys([
					'id' => 'primary',
					'field_id' => 'key',
				])
				->execute()
				->success();
		}
	}

