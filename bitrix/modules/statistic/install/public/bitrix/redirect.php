<?
define("ADMIN_SECTION",false);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
if(CModule::IncludeModule("statistic"))
{
	if($_REQUEST["site_id"] == '')
	{
		$site_id = false;
		$referer_url = $_SERVER["HTTP_REFERER"] == ''? $_SESSION["SESS_HTTP_REFERER"]: $_SERVER["HTTP_REFERER"];
		if($referer_url <> '')
		{
			$url = @parse_url($referer_url);
			if($url)
			{
				$rs = CSite::GetList("LENDIR", "DESC", Array("ACTIVE" => "Y", "DOMAIN" => "%".$url["host"], "IN_DIR" => $url["path"]));
				if($arr = $rs->Fetch())
				{
					$site_id = $arr["ID"];
				}
			}
		}
	}
	else
	{
		$site_id = $_REQUEST["site_id"];
	}
	$goto = preg_replace("/#EVENT_GID#/i", urlencode(CStatEvent::GetGID($site_id)), $_REQUEST["goto"]);
	CStatEvent::AddCurrent($_REQUEST["event1"], $_REQUEST["event2"], $_REQUEST["event3"], $_REQUEST["money"], $_REQUEST["currency"], $goto, $_REQUEST["chargeback"], $site_id);
}
else
{
	$goto = preg_replace("/#EVENT_GID#/i", "", $_REQUEST["goto"]);
}
LocalRedirect($goto);
?>