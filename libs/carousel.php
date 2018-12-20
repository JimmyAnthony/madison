<?php

class carousel{

	private $data = array();
	private $html = '';
	private $js = '';

	public function __construct(){

	}

	public function set_data($array){
		$this->data = $array;
	}

	public function set_js($js){
		$this->js = $js;
	}

	public function get_html(){
		$rs = $this->group_archivador();
		//var_dump($rs);
		$html = '<div style="width: 350px; height: 250px; border: 1px solid #4C4C4C; border-radius: 5px 5px 5px 5px;">';
			$html.='<div id="myCarousel" class="carousel slide">';
				$html.='<div class="carousel-inner">';
				$vj = 0;
				$vk = 2;
				$array_search = array();
				for($vi = 0; $vi < count($rs); $vi += 2 ){
					$vk += $vi; 
					$html.='<div class="'.($vi == 0 ? 'active' : '').' item">';
					for($vj; $vj < count($rs); ++$vj){
						if ($vj < $vk){
							$html.='<div class="div_archive">';
								$html.='<div class="archive">';
								foreach($rs[$vj]['hijos'] as $index => $value){
									$r = array_search(intval($rs[$vj]['archiv_id']).'-'.intval($value['estante_fila']), $array_search);
									if (!is_numeric($r)){
										$array_search[count($array_search)] = intval($rs[$vj]['archiv_id']).'-'.intval($value['estante_fila']);
										$px = 0;
										foreach($rs[$vj]['hijos'] as $index01 => $value01){
											if (intval($value01['estante_fila']) == intval($value['estante_fila'])){
												$px+=48;
											}
										}
										$html.='<div style="width:'.$px.'px;">';
										foreach($rs[$vj]['hijos'] as $index02 => $value02){
											if (intval($value02['estante_fila']) == intval($value['estante_fila'])){
												$html.='<div class="items"><a title="Entrar ..." href="#" onclick="'.$this->js.'('.$value02['estante_id'].')">  '.$value02['estante_label'].'<img src="/images/icon/logout.png" ></a></div>';
											}
										}
										$html.='</div>';
									}
								}
								$html.='<div class="caption">'.$rs[$vj]['archiv_label'].'</div>';
								$html.='</div>';
							$html.='</div>';
						}else{
							break;
						}
					}
					$html.='</div>';
				}

				$html.='</div>';
				$html.='<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>';
				$html.='<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>';
			$html.='</div>';
		$html.='</div>';
		return $html;
	}

	public function group_archivador(){
		$array_result = array();
		$array_search = array();
		$vj = 0;
		foreach($this->data as $index => $value){
			$r = array_search(intval($value['archiv_id']), $array_search);
			if (!is_numeric($r)){
				$vi = 0;
				$array_search[count($array_search)] = intval($value['archiv_id']);
				$array_result[$vj]['archiv_id'] = intval($value['archiv_id']);
				$array_result[$vj]['archiv_label'] = trim($value['archiv_label']);
				$array_result[$vj]['hijos'] = array();
				if (intval($value['estante_id']) != 0){
					$array_result[$vj]['hijos'][$vi]['estante_id'] = intval($value['estante_id']);
					$array_result[$vj]['hijos'][$vi]['estante_fila'] = intval($value['estante_fila']);
					$array_result[$vj]['hijos'][$vi]['estante_columna'] = intval($value['estante_columna']);
					$array_result[$vj]['hijos'][$vi]['estante_stock'] = intval($value['estante_stock']);
					$array_result[$vj]['hijos'][$vi]['estante_label'] = trim($value['estante_label']);
					
				}
				++$vj;
			}else{
				++$vi;
				$array_result[$r]['hijos'][$vi]['estante_id'] = intval($value['estante_id']);
				$array_result[$r]['hijos'][$vi]['estante_fila'] = intval($value['estante_fila']);
				$array_result[$r]['hijos'][$vi]['estante_columna'] = intval($value['estante_columna']);
				$array_result[$r]['hijos'][$vi]['estante_stock'] = intval($value['estante_stock']);
				$array_result[$r]['hijos'][$vi]['estante_label'] = trim($value['estante_label']);
			}
		}
		return $array_result;
	}

}