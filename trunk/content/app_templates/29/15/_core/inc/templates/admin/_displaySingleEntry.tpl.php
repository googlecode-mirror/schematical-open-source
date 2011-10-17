		<tr>
			<?php 			 
			foreach(admin::$arrAnswerFields as $intIndex=>$strAnswerField){ ?>
				<td>
					<?= FBContestApplication::RenderContestField($strAnswerField); ?>
				</td>
			<?php } ?>			
			<td>
				<a class='MFBDeleteBtn' href='#' action_parameter='<?= FBContestApplication::GetCurrentEntry()->IdContestEntry; ?>'>
					Delete
				</a>
			</td>
		</tr>