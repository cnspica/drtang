<?php
include ("fetion.php");
class BloodCmp{
	// 设置处理标准 (可以设置从配置文件中进行读取)
	private $StdHigh = 178;													// 测量血糖值高压阀值标准
	private $StdLow = 100;													// 测量血糖值低压阀值标准
	
	private $StdHighLevel = 10;												// 测量血糖值轻重度高压标准
	private $StdLowLevel = 10;												// 测量血糖值轻重度低压标准		

	private $StdRateH = 0.3;												// 正向变化趋势血糖标准
	private $StdRateL = -0.3;												// 负向变化趋势血糖标准
	private $N = 10;														// 对 N 个血糖值取平均值
	
	// 获取手机端的数据
	private $Telephone;
	private $BloodData;

	// 获取数据库中的信息
	private $Aver = 110;													// 获取前 N 次的测量平均值

	// 构造函数
	function __construct($Tphone, $BData)
	{
		$this->BloodData = $BData;
		$this->Telephone = $Tphone;
	}
	
	// 云端日志记录
	function RecordLog($result)
	{
		date_default_timezone_set('Etc/GMT-8');							    //这里设置了时区
		$nowtime = date("Y-m-d H:i:s");
		
		$filename = "log/" . $this->Telephone . ".txt";
		$fp = fopen($filename, "a");
		$tmp = "电话:" . $this->Telephone . " \n时间:" . $nowtime . "\n血糖值:" . $this->BloodData . "\n结果:" . $result . "\n\n";
		fwrite($fp, $tmp);
		fclose($fp);
	}
	
	// 进行单次阀值比较
	public function Blood_cmp()
	{
		$leap = 1;
		$temph = $this->BloodData - $this->StdHigh;							// 本次测量值和高压阀值的比较
		$templ = $this->BloodData - $this->StdLow;							// 本次测量值和低压阀值的比较
		if ($this->BloodData > $this->StdHigh)								// 超出高压阀值
		{
			if ($temph < $this->StdHighLevel)
			{
				$level = 1;													// 轻度高压, level 进行等级设定
			}
			else
			{	
				$level = 2;													// 重度高压
			}
			$leap = 0;
			echo "\n\n\t超出了阀值,进行飞信通知用户!\n\n";
		}
		elseif ($this->BloodData < $this->StdLow)							// 超出低压阀值
		{
			if ($templ < $this->StdHighLevel)
			{
				$level = -1;												// 轻度低压
			}
			else 
			{
				$level = -2;												// 重度低压
			}
			$leap = 0;
			echo "\n\n\t超出了阀值,进行飞信通知用户!\n\n";
		}
		else																// 单次阀值判断正常
		{
			echo "\n\n\t单次测量正常!\n\n";
			$temp = (($this->Aver * 9 + $this->BloodData) / 10);
			$rate = $temp / $this->Aver - 1;
			if ($rate > $this->StdRateH)									// 高压趋势判断, rate 记录变化趋势
			{
				$level = 3;
				$leap = 0;
				echo "\n\n\t超出了阀值比率!\n\n";
			}
			elseif ($rate < $this->StdRateL)								// 低压趋势判断
			{
				$level = -3;
				$leap = 0;
				echo "\n\n\t超出了阀值比率!\n\n";
			}
			else															// 本次测量正常
			{
				echo "\n\n\t趋势判断正常, 不用飞信提醒用户\n\n";
			}
		}	
		if ($leap == 1)
		{
			$result = "正常";
		}
		else
		{
			$SendFetion = new Fetion($level, $this->Telephone, "");
			$SendFetion->fetion();
			$result = "不正常";
		}
		$this->RecordLog($result);											// 进行云端日志记录
		return $result;														// 写如数据库（仅仅写 result 的返回结果，和记录数值）
	}
}
?>
