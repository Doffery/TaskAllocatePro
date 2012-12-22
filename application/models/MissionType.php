<?php

namespace application\models;

class MissionType {
	public static final $tests_num = array (
			'unit_test' => 1,
			'inte_test' => 2,//integeration
			'syst_test' => 3,
			'syst_function_test' => 4,
			'syst_security_test' => 5,
			'syst_volume_test' => 6,//volume
			'syst_integrity_test' => 7,//integrity
			'syst_structural_test' => 8,//structural
			'syst_ui_test' => 9,
			'syst_load_test' => 10,
			'syst_pressure_test' => 11,//pressure
			'syst_stra_test' => 12,//strain 
			'syst_recovery_test' => 13,
			'syst_configuration_test' => 14,
			'syst_compatibility_test' => 15,
			'syst_installation_test' => 16,
			'else_test' => 17
			);
	
	public static final $tests_str = array (
			1 => '单元测试',
			2 => '集成测试',
			3 => '系统测试',
			4 => '功能测试',
			5 => '安全性测试',
			6 => '容量测试',
			7 => '完整性测试',
			8 => '结构测试',
			9 => '用户界面测试',
			10 => '负载测试',
			11 => '压力测试',
			12 => '疲劳强度测试',
			13 => '恢复性测试',
			14 => '配置测试',
			15 => '兼容性测试',
			16 => '安装测试',
			17 => '其他测试'
			);
	
	
	public static function testNum($str) {
		return $tests_num[$str];
	}
}

?>