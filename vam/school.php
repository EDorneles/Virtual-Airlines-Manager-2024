<?php
	/**
	 * @Project: Virtual Airlines Manager (VAM)
	 * @Author: Alejandro Garcia
   * @New_template: Jonatha Silva
	 * @Web http://virtualairlinesmanager.net
	 * Copyright (c) 2013 - 2016 Alejandro Garcia
	 * VAM is licenced under the following license:
	 *   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
	 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
	 */
?>
<?php
	$school = '';
	/* Connect to Database */
	$db = new mysqli($db_host , $db_username , $db_password , $db_database);
	$db->set_charset("utf8");
	if ($db->connect_errno > 0) {
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	// Execute SQL query
	$sql = "select * from web_configurations";
	if (!$result = $db->query($sql)) {
		die('There was an error running the query  [' . $db->error . ']');
	}
	while ($row = $result->fetch_assoc()) {
        $school=$row["school"] ;
	}
	$db->close();
	?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<div class="row">
	<div class="col-md-12">
          <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><IMG src="images/icons/ic_chat_white_18dp_1x.png">&nbsp;<?php echo "Registration proccess"; ?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
           
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
	

			<!-- Default panel contents -->
	
			<!-- Table -->
			<table class="table">
        <center><a href="http://www.privatevirtual.com.br/" target="_blank"><img src="http://www.privatevirtual.com.br/vam/images/logo_vam.png" WIDTH="128" HEIGHT="128" BORDER=0 ALT="20"></a></center> 
				<center><h3><strong>Registre-se na <?php echo $va_name; ?> </strong></h3></center>
			</div>
            <div class="panel-body">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;Bem-vindo ao processo de seleção de Pilotos de Táxi de Avião Privado, durante o qual, os membros da Staff verificarão suas atitudes sobre conhecimentos aeronáuticos, com as limitações lógicas da simulação, bem como conhecimento da companhia aérea, frota e destinos.<p>

                                       <strong></strong></p><h4><strong>• Sistema de classificação</strong></h4>
			&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;Processo é um questionário de 10 perguntas e uma pontuação máxima de 100 pontos.<p></p>
                                        
										
                                      <strong></strong><h4><strong>• Licenciamento</strong></h4>  
							&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;Apenas selecionaremos questionários que:<p></p><p>
                                        <li> Supera a pontuação mínima de 70/100 pontos; </p><p> 
                                        <li> Cuja pontuação esteja dentro da margem dos lugares oferecidos, tendo em conta a pontuação máxima como nº 1.</p><p>
										&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;Dada a eventual igualdade de pontos são tidos em conta: 1º Melhor pontuação nas questões a desenvolver 2º Horas de voo IVAO 3º Entrevista pessoal.</p><p>
										
										<strong>AVISO:</strong> Em caso de solicitação de inscrições, a STAFF considerará a possibilidade de aumentar as vagas para novos pilotos.</p><p></p>
<br>
                                       <center><strong></strong><p></p><h4><strong>Questionário de Candidatura [Atenção!]</strong></h4></center><br>
                                       &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;Para aceder ao questionário é necessário inscrever-se na Private Plane Táxi clicando no link abaixo e depois em "Vestibular". Depois de receber seu e-mail de confirmação, você pode inserir seu nome de usuário e senha. <p> 
<br>
                                        <center><h4><strong>Registre-se na Private Plane Taxi</strong></h4></center><br>
                                        &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;Assim que o teste e sua pontuação estiverem dentro da cota de vagas oferecidas, você receberá a confirmação via e-mail de sua renda como piloto de linha aérea com detalhes de sua nova convocação para logar na web.<p> 


                                        <strong></strong></p><strong>AVISO:</strong> Este processo será supervisionado pelo pessoal da companhia aérea e garantirá a proteção dos dados dos usuários envolvidos no mesmo. O pessoal da PPT Virtual reserva-se o direito de recusar a admissão de candidaturas a este processo.<p></p></div>					
						<align="justify">
					</align="justify"></div>
					<hr>
              <!-- 
              AFTER CREATING YOUR ACADEMY YOU MAY NEED TO CHANGE THE LINK BELLOW TO YOUR ACADEMY'S WEBSITE. 
              -->
					<div class="text-center center-block">
					<a href="http://www.privatevirtual.com.br/vam/exames/candidatos.php" target="_blank"><button type="button" class="btn btn-info btn-lg"><align="center"><strong></strong><h5><strong>ENTRE NO EXAME</strong></h5></align="center"></button></a>
			</table>
		</div><br>
	</div>
</div>
