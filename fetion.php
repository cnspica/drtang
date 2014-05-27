<?php

include_once("conn.php");

class Fetion{
	private $url = "http://127.0.0.1/drtang/Fetion/feixinclass.php?user=";
	private $SendTel = "**********";						// 飞信帐号
	private $SendTelPwd = "************";					// 飞信密码
	private $SendTelTo;
	private $lev;
	private $message;

	// 构造函数
	function __construct($level, $tel, $msg)
	{
		$this->lev = $level;
		$this->SendTelTo = $tel;
		$this->message = $msg;
	}

	function GetDatabase($result)
	{
		$sqlresult = mysql_query("SELECT * FROM guardian WHERE phonenum = ".$this->SendTelTo);
		while ($row = mysql_fetch_array($sqlresult))
		{
			$this->SendInfo($result, $this->url, $this->SendTel, $this->SendTelPwd, $row['guardiantel']);
		}
	}

	// 飞信发送
	function SendInfo($result, $url, $SendTel, $SendTelPwd, $SendTelTo)
	{
		$address = $url . $SendTel . "&passwd=" . $SendTelPwd . "&to=" . $SendTelTo . "&msg=" . $result;
		file_get_contents($address);
	}

	// 利用飞信给用户发送信息
	public function fetion()
	{
		if ($this->message == "")
		{
			if ($this->lev == 0)
			{
				$result = "受监护人的血糖值正常，请继续保持";
			}
			elseif ($this->lev == 1)
			{
				$result = "受监护人的血糖值偏高，请注意身体";
			}
			elseif ($this->lev == 2)
			{
				$result = "受监护人的血糖值非常高，请及时去医院进行检查身体";
			}
			elseif ($this->lev == -1)
			{
				$result = "受监护人的血糖值偏低，请注意身体";
			}
			elseif ($this->lev == -2)
			{
				$result = "受监护人的血糖值非常低，请及时去医院进行检查身体";
			}
			else
			{
				$result = 0;
				echo "血糖值有误!\n";
			}
		}
		else
		{
			$result = $this->message;
		}
		$this->GetDatabase($result);
		return $result;
	}
}
?>
