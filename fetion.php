<?php
class Fetion{
	private $url = "https://quanapi.sinaapp.com/fetion.php?u=";
	private $SendTel = "***********";						// 飞信帐号
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

	// 飞信发送
	function SendInfo($result, $url, $SendTel, $SendTelPwd, $SendTelTo)
	{
		$address = $url . $SendTel . "&p=" . $SendTelPwd . "&to=" . $SendTelTo . "&m=" . $result;
		file_get_contents($address);
	}
	
	// 利用飞信给用户发送信息
	public function fetion()
	{
		if ($this->message == "")
		{
			if ($this->lev == 0)
			{
				$result = "您的血糖值正常，请继续保持";
			}
			elseif ($this->lev == 1)
			{
				$result = "您的血糖值偏高，请您注意身体";
			}
			elseif ($this->lev == 2)
			{
				$result = "您的血糖值非常高，请您去医院进行检查身体";
			}
			elseif ($this->lev == -1)
			{
				$result = "您的血糖值偏低，请您注意身体";
			}
			elseif ($this->lev == -2)
			{
				$result = "您的血糖值非常低，请您去医院进行检查身体";
			}
			else
			{
				echo "血糖值有误!\n";
				exit(0);
			}
		}
		else
		{
			$result = $this->message;
		}
		$this->SendInfo($result, $this->url, $this->SendTel, $this->SendTelPwd, $this->SendTelTo);
		return $result;
	}
}
?>
