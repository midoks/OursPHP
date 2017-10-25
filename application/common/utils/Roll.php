<?php

// +----------------------------------------------------------------------
// | oursphp [ simple and fast ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: midoks <627293072@qq.com>
// +----------------------------------------------------------------------

namespace frame;

class Roll {
	
	/**
	 * 按权重随机选择数组中的一个元素，数组中每个元素含权重列
	 * @param array $ary 随机选择的数组
	 * @param string $key 指明数组元素中权重列名，默认为weight
	 * @return array $ary[$k]
	 */
	public static function select(array $ary, $key = 'weight') {
		$k = self::selectKey($ary, $key);
		return $ary[$k];
	}

	/**
	 * 按权重随机选择数组中的一个元素，数组中每个元素含权重列
	 * @param array $ary 随机选择的数组
	 * @param string $key 指明数组元素中权重列名，默认为weight
	 * @return int
	 */
	public static function selectKey(array $ary, $key = 'weight') {
		$weight = array();
		foreach ($ary as $k => $v) {
			if (isset($v[$key])){
				$weight[$k] = $v[$key];
			}
		}
		
		$k = self::rollKey($weight);
		return $k;
	}

    /**
     * 给定一个数组，value是权重，按权重随机返回key
     * @param array $weight
     * @return int|string
     * @throws BizException
     */
	private static function rollKey(array $weight) {
		$roll = rand(1, array_sum($weight));
	
		$tmpW = 0;
		foreach ($weight as $k => $v) {
			$min 	= $tmpW;
			$tmpW 	+= $v;
			$max 	= $tmpW;
			if ($roll >= $min && $roll <= $max) {
				return $k;
			}
		}
		throw new BizException('权重选择出错');
	}	
	
}