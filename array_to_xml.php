<?php
/**
 * convert array to xml
 * @link https://github.com/P54l0m5h1k/array_to_xml
 * @link https://github.com/P54l0m5h1k/xml_to_array - twin brother
 */
class array_to_xml
{
	/**
	 * convert array to xml
	 * @public
	 * @static
	 * @example arr2xml::convert(['root'=> ['data'=> ['value'=> 1]]]);
	 * @example <root><data><value>1</value></data></root>
	 * @example arr2xml::convert(['data'=> ['id'=> 'ID', 'info'=> null, '@info'=> ['attr'=> 'one'], 'more'=> [1, 2]]]);
	 * @example <data><id>ID</id><info attr="one"></info><more>1</more><more>2</more></data>
	 * @static
	 * @param array $arr  - basis array for creating xml
	 * @param bool  $head - return with head tag
	 * @return string
	 */
	public static function convert(array $arr, $head = true)
	{
		return ($head ? '<?xml version="1.0" encoding="utf-8"?>' : '').self::var2tag($arr);
	}

	/**
	 * make tag from variable
	 * @private
	 * @static
	 * @param mixed $arr basis array for creating xml
	 * @param int   $tab depth level of elements
	 * @return string
	 */
	private static function var2tag($arr, $tab = 0)
	{
		if (!is_array($arr))
			return $arr;
		$xml = '';
		foreach ($arr as $key=> $value):
			if ($key{0} == '@'):
				$key = substr($key, 1);
				$xml .= "\n".self::tab($tab).'<'.$key.' '.self::arr2attr($value).'>'.self::var2tag($arr[$key], $tab + 1).'</'.$key.'>';
			elseif (!is_null($value)):
				if (is_array($value) and array_key_exists(0, $value)):
					$xml .= "\n";
					foreach ($value as $val)
						$xml .= self::tab($tab).'<'.$key.'>'.$val.'</'.$key.'>'."\n";
				else:
					if (is_array($value) and !array_key_exists('@'.$key, $value))
						$xml .= "\n".self::tab($tab).'<'.$key.'>'.self::var2tag($value, $tab + 1).self::tab($tab).'</'.$key.'>'."\n";
					else
						$xml .= "\n".self::tab($tab).'<'.$key.'>'.$value.'</'.$key.'>'."\n";
				endif;
			endif;
		endforeach;
		return $xml;
	}

	/**
	 * repeat tab symbol
	 * @private
	 * @static
	 * @param int $tab times repeated
	 * @return string
	 */
	private static function tab($tab)
	{
		return str_repeat("\t", $tab);
	}

	/**
	 * array to string with attributes
	 * @private
	 * @static
	 * @example arr2xml::arr2attr(['name'=>'my_input', 'placeholder'=> 'input here...']);
	 * @param array $attr array with attributes
	 * @return string
	 */
	private static function arr2attr(array $attr)
	{
		$attr_str = '';
		foreach ($attr as $property => $value):
			if (is_null($value))
				continue;
			if (is_numeric($property))
				$property = $value;
			$attr_str .= $property.'="'.$value.'" ';
		endforeach;
		return trim($attr_str);
	}
}