<?php

namespace Goose\Scripts;

class EncryptDocs extends \Frame\Script {

	public function run() {
		$org = "hello world!";
		$key = "goodgoodgoodgood";

		$ret = \Goose\Libs\Util\Util::gsk_encrypt($key,$org );

		echo "encrypt ret = {$ret}\n";

		$ret = \Goose\Libs\Util\Util::gsk_decrypt($key,$ret );

		echo "decrypt ret = {$ret}\n";
	}
}