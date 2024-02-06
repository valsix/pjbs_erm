
      <?php

      $rs = $this->data['rows'];
      $no=1;
      $top_inheren = array();
      $top_paska_kontrol = array();
      $top_paska_mitigasi = array();
      if(is_array($rs))
        foreach($rs as $r => $val){
          if($id_risiko_onlyone && $val['id_risiko']!=$id_risiko_onlyone){
            $no++;
            continue;
          }

          $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
          $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
          $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

          $no++;
      }
    $no_tooltip=true;
    include "_matrix.php"; ?>