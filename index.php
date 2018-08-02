<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Test XML">
  <meta name="keywords" content="HTML,CSS,XML,jQuery">
  <meta name="author" content="Narcisa Angheloiu">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <style type="text/css">
    .table {
    display: table;
    width: 500px;
        }
    .tr { 
        display: table-row; 
    }
    .td { 
        display: table-cell;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        padding:9px;
    }
    .italic{
        font-style:italic;
    }
    .italic:before {
        content:"\28";
    }
    .italic:after {
        content:"\29";
    }
    .bordercorner{
        border-top: 1px solid black;
        border-right: 1px solid black;
    }
    .bordertop{
        border-top: 1px solid black;
    }
    .borderright{
        border-right: 1px solid black;
    }
    #headertable{
        font-weight: bold;
    }
    
  </style>
</head>
<body>
<div style="margin-left:10px;">
<?php
$xml=simplexml_load_file("countriesvalid.xml") or die("Error: Cannot create object");
    /* --- info ------
     * creez un vector multidimensional care va colecta  nodurile  ce urmeaza  a  fi  sortate in cheile acestuia
     */
    $sortable = array();

    foreach($xml->country as $node) {
    $sortable[(string)($node['zone'])][(string)($node->name)] = $node;
   
    /* --- info ------
     * extrag 'latitudinea' si 'longitudinea' din url 
     * apeland functia extract_infomaps creata mai jos
     */
    $infomaps = extract_infomaps( (string)($node->map_url),'/@(.+?)data/s') ;
    $sortable[(string)($node['zone'])][(string)($node->name)]['latitudine'] = $infomaps[0];
    $sortable[(string)($node['zone'])][(string)($node->name)]['longitudine'] = $infomaps[1];
    
    /* --- info ------
     * sortez nodurile(cheile vectorului multidimensional, dupa regiune  si  tara)
     */
    ksort($sortable);
    ksort($sortable[(string)($node['zone'])]);
    }// endforeach

    // print_r($sortable);
    
    /*--- info ------
    * @param string $line 
    * @param string $match 
    * @return array
    */
  function extract_infomaps( $line, $match){
    preg_match('/@(.+?)data/s', $line, $match);
    return $data[] = explode(',',trim($match[1]));
   }
   /*--- info ------
    * @param object $xpath 
    * @param string $currencycode 
    * @return string
    */
  function ListOfCurrencies($xpath, $currencycode){
    foreach ($xpath as $data) {
        if((string)$data->currency['code'] == $currencycode)
            echo  '<h3>'.$data->name.'</h3>';
    }
  }
?>
 <br><br>

 <?php  if(is_array($sortable) && count($sortable)> 0): ?>
 <div class="table">
    <div class="tr" id="headertable">
        <div class="td bordertop ">Regiune
            <select id="zone" >
                <option value="0">Choose</option>
                <?php foreach($sortable as $zone => $element): ?>
                   <option value="<?php echo $zone ?>"  onchange="test()">
                      <?php echo $zone ?>
                   </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="td bordertop">Țară</div>
        <div class="td bordertop">Limbă</div>
        <div class="td bordertop">Monedă</div>
        <div class="td bordertop">Latitudine</div>
        <div class="td bordertop borderright">Longitudine</div>
    </div>
       <?php foreach($sortable as $zone => $element): ?>
            <?php if(count($element) > 0): ?>
                <?php foreach($element as $country => $data): ?>
                <div class="tr <?php echo $zone ?>">
                    <div class="td"><?php echo $zone ?></div>
                    <div class="td">
                        <?php echo $country ?>
                        <span class="italic"><?php echo (string)$data->name['native'] ?></span>
                    </div>
                    <div class="td">
                        <?php  echo (string)$data->language; ?>
                        <span class="italic"><?php echo (string)$data->language['native']; ?></span>
                    </div>
                    <div class="td"><?php  echo (string)$data->currency; ?>&nbsp;<span class="italic"><?php echo (string)$data->currency['code']; ?></span></div>
                    <div class="td"><?php  echo (string)$data['latitudine']; ?></div>
                    <div class="td borderright"><?php  echo (string)$data['longitudine']; ?></div>
                </div>
                <?php endforeach;  ?>
            <?php endif; ?>
       <?php endforeach;  ?>
  </div>
<?php endif; ?>
<br><br><hr>
<h1>Numele țărilor care au moneda “Euro”</h1>
<?php ListOfCurrencies($xml->xpath('/countries/country'), 'EUR'); ?>
</div>
</body>
<script>
   $("#zone").on("change",
               function(){
                   var a = $(this).find("option:selected").val();
                  // alert(a);
                   $("div.tr").each(
                      function(){
                          $('#headertable').show();
                           if($( this ).hasClass( a)){
                              $(this).show();
                           }
                           else{
                               $(this).hide();
                           }
                        if(a=='0')
                        $(this).show();  
                       });   
               });
</script>
</html>