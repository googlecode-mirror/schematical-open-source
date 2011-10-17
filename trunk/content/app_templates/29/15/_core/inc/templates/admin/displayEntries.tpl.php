<?php $this->RenderHeader(); ?>
	<table id='tblDisplayEntries'>
		<tr>
			<?php 
			foreach (admin::$arrAnswerFields as $intIndex=>$strAnswerField){ ?>
				<th>
					<?= $strAnswerField; ?>
				</th>
			<?php } ?>
		</tr>
		<?php $this->RenderEntries(); ?>
	</table>

<?php $this->RenderFooter(); ?>