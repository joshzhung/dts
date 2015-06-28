<?php

namespace skill21
{
	function init() 
	{
		define('MOD_SKILL21_INFO','club;hidden;');
	}
	
	function acquire21(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost21(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function evonpc($xtype,$xname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','skill21','lvlctl','logger'));
		if(!$xtype || !$xname){return false;}
		if(!isset($enpcinfo[$xtype])){return false;}
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE type = '$xtype' AND name = '$xname'");
		$num = $db->num_rows($result);
		if(!$num){return false;}	
		if(!isset($enpcinfo[$xtype][$xname])){return false;}
		$npc=$enpcinfo[$xtype][$xname];
		$npc['hp'] = $npc['mhp'];
		$npc['sp'] = $npc['msp'];
		$npc['exp'] = round(($npc['lvl']*2+1)*$baseexp);
		if(!isset($npc['state'])){$npc['state'] = 0;}
		$npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];
		unset($npc['skill']);
		return $npc;
	}

	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		eval(import_module('logger'));
		if (\skillbase\skill_query(21,$pd))
		{
			$npcdata = evonpc($pd['type'],$pd['name']);
			$log .= '<span class="yellow">'.$pd['name'].'却没死去，反而爆发出真正的实力！</span><br>';
			if($npcdata){
				addnews($now , 'evonpc',$pd['name'], $npcdata['name'], $pa['name']);
				foreach($npcdata as $key => $val){
					$pd[$key] = $val;
				}
				\skillbase\skill_lost(21,$pd);
				$pd['npc_evolved'] = 1;
			}	
		}
	}
	
	function counter_assault_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['npc_evolved']) return;	//进化的NPC本轮不反击
		$chprocess($pa, $pd, $active);
	}	
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'evonpc') {
			if($a == 'Dark Force幼体'){
				$nword = "<span class=\"lime\">{$c}击杀了{$a}，却没料到这只是幻影……{$b}的封印已经被破坏了！</span>";
			}elseif($a == '小莱卡'){
				$nword = "<span class=\"lime\">{$c}击杀了{$a}，却发现这只是幻象……真正的{$b}受到惊动，方才加入战场！</span>";
			}else{
				$nword = "<span class=\"lime\">{$c}击杀了{$a}，却发现对方展现出了第二形态：{$b}！</span>";
			}
			return "<li>{$hour}时{$min}分{$sec}秒，$nword<br>\n";
		}
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>