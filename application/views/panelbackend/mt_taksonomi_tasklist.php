
  <table class="table table-striped table-hover dataTable">
    <thead>
        <tr>
            <th width="10px">No</th>
            <?php foreach($header as $r){
                echo "<th>$r[label]</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
    <?php
    $row = $this->post;
    $i = $page;
    foreach($list['rows'] as $rows){
    	$i++;
        $id_taksonomi_risiko = $rows['id_taksonomi_risiko'];

        $arrr = array(''=>'');
        if($row['id_taksonomi_objective'][$id_taksonomi_risiko])
            $arrr += $areaarr[$row['id_taksonomi_objective'][$id_taksonomi_risiko]];
        else{
            foreach($areaarr as $r){
                $arrr += $r;
            }
        }

    	echo "<tr>";
    	echo "<td>$i</td>";
        echo "<td style='padding:3px;' width='120px'>".UI::createSelect('id_taksonomi_objective['.$id_taksonomi_risiko.']',$objectivearr,$row['id_taksonomi_objective'][$id_taksonomi_risiko],true,'form-control ',"style='width:100%;' onchange='goSubmit(\"save\")'")."</td>";
        echo "<td style='padding:3px' width='370px'>".UI::createSelect('id_taksonomi_area['.$id_taksonomi_risiko.']',$arrr,$row['id_taksonomi_area'][$id_taksonomi_risiko],true,'form-control ',"style='width:100%;'")."</td>";
        echo "<td>$rows[nama]</td>";
    	echo "</tr>";
    }
    if(!count($list['rows'])){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?php if($list['rows']){ ?>
  <div style="text-align: right; padding: 10px">
      <button type="button" onclick="if(confirm('Apakah Anda akan menyimpan ?')){goSubmit('save_taksonomi')}" class="btn btn-primary">Save</button>
  </div>
  <?php } ?>