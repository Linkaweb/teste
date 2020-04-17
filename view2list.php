<?php
namespace PHPMaker2020\leads_2;

// Session
if (session_status() !== PHP_SESSION_ACTIVE)
	session_start(); // Init session data

// Output buffering
ob_start();

// Autoload
include_once "autoload.php";
?>
<?php

// Write header
WriteHeader(FALSE);

// Create page object
$view2_list = new view2_list();

// Run the page
$view2_list->run();

// Setup login status
SetupLoginStatus();
SetClientVar("login", LoginStatus());

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view2_list->Page_Render();
?>
<?php include_once "header.php"; ?>
<?php if (!$view2_list->isExport()) { ?>
<script>
var fview2list, currentPageID;
loadjs.ready("head", function() {

	// Form object
	currentPageID = ew.PAGE_ID = "list";
	fview2list = currentForm = new ew.Form("fview2list", "list");
	fview2list.formKeyCountName = '<?php echo $view2_list->FormKeyCountName ?>';
	loadjs.done("fview2list");
});
var fview2listsrch;
loadjs.ready("head", function() {

	// Form object for search
	fview2listsrch = currentSearchForm = new ew.Form("fview2listsrch");

	// Dynamic selection lists
	// Filters

	fview2listsrch.filterList = <?php echo $view2_list->getFilterList() ?>;
	loadjs.done("fview2listsrch");
});
</script>
<script>
loadjs.ready("head", function() {

	// Client script
	// Write your client script here, no need to add script tags.

});
</script>
<?php } ?>
<?php if (!$view2_list->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($view2_list->TotalRecords > 0 && $view2_list->ExportOptions->visible()) { ?>
<?php $view2_list->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($view2_list->ImportOptions->visible()) { ?>
<?php $view2_list->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($view2_list->SearchOptions->visible()) { ?>
<?php $view2_list->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($view2_list->FilterOptions->visible()) { ?>
<?php $view2_list->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$view2_list->renderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if (!$view2_list->isExport() && !$view2->CurrentAction) { ?>
<form name="fview2listsrch" id="fview2listsrch" class="form-inline ew-form ew-ext-search-form" action="<?php echo CurrentPageName() ?>">
<div id="fview2listsrch-search-panel" class="<?php echo $view2_list->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view2">
	<div class="ew-extended-search">
<div id="xsr_<?php echo $view2_list->SearchRowCount + 1 ?>" class="ew-row d-sm-flex">
	<div class="ew-quick-search input-group">
		<input type="text" name="<?php echo Config("TABLE_BASIC_SEARCH") ?>" id="<?php echo Config("TABLE_BASIC_SEARCH") ?>" class="form-control" value="<?php echo HtmlEncode($view2_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo HtmlEncode($Language->phrase("Search")) ?>">
		<input type="hidden" name="<?php echo Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?php echo Config("TABLE_BASIC_SEARCH_TYPE") ?>" value="<?php echo HtmlEncode($view2_list->BasicSearch->getType()) ?>">
		<div class="input-group-append">
			<button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?php echo $Language->phrase("SearchBtn") ?></button>
			<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?php echo $view2_list->BasicSearch->getTypeNameShort() ?></span></button>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item<?php if ($view2_list->BasicSearch->getType() == "") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this);"><?php echo $Language->phrase("QuickSearchAuto") ?></a>
				<a class="dropdown-item<?php if ($view2_list->BasicSearch->getType() == "=") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, '=');"><?php echo $Language->phrase("QuickSearchExact") ?></a>
				<a class="dropdown-item<?php if ($view2_list->BasicSearch->getType() == "AND") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'AND');"><?php echo $Language->phrase("QuickSearchAll") ?></a>
				<a class="dropdown-item<?php if ($view2_list->BasicSearch->getType() == "OR") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'OR');"><?php echo $Language->phrase("QuickSearchAny") ?></a>
			</div>
		</div>
	</div>
</div>
	</div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $view2_list->showPageHeader(); ?>
<?php
$view2_list->showMessage();
?>
<?php if ($view2_list->TotalRecords > 0 || $view2->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($view2_list->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> view2">
<form name="fview2list" id="fview2list" class="form-inline ew-form ew-list-form" action="<?php echo CurrentPageName() ?>" method="post">
<?php if ($Page->CheckToken) { ?>
<input type="hidden" name="<?php echo Config("TOKEN_NAME") ?>" value="<?php echo $Page->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view2">
<div id="gmp_view2" class="<?php echo ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($view2_list->TotalRecords > 0 || $view2_list->isGridEdit()) { ?>
<table id="tbl_view2list" class="table ew-table"><!-- .ew-table -->
<thead>
	<tr class="ew-table-header">
<?php

// Header row
$view2->RowType = ROWTYPE_HEADER;

// Render list options
$view2_list->renderListOptions();

// Render list options (header, left)
$view2_list->ListOptions->render("header", "left");
?>
<?php if ($view2_list->idLead->Visible) { // idLead ?>
	<?php if ($view2_list->SortUrl($view2_list->idLead) == "") { ?>
		<th data-name="idLead" class="<?php echo $view2_list->idLead->headerCellClass() ?>"><div id="elh_view2_idLead" class="view2_idLead"><div class="ew-table-header-caption"><?php echo $view2_list->idLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idLead" class="<?php echo $view2_list->idLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->idLead) ?>', 1);"><div id="elh_view2_idLead" class="view2_idLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->idLead->caption() ?></span><span class="ew-table-header-sort"><?php if ($view2_list->idLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->idLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->emailLead->Visible) { // emailLead ?>
	<?php if ($view2_list->SortUrl($view2_list->emailLead) == "") { ?>
		<th data-name="emailLead" class="<?php echo $view2_list->emailLead->headerCellClass() ?>"><div id="elh_view2_emailLead" class="view2_emailLead"><div class="ew-table-header-caption"><?php echo $view2_list->emailLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="emailLead" class="<?php echo $view2_list->emailLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->emailLead) ?>', 1);"><div id="elh_view2_emailLead" class="view2_emailLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->emailLead->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($view2_list->emailLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->emailLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->phoneLead->Visible) { // phoneLead ?>
	<?php if ($view2_list->SortUrl($view2_list->phoneLead) == "") { ?>
		<th data-name="phoneLead" class="<?php echo $view2_list->phoneLead->headerCellClass() ?>"><div id="elh_view2_phoneLead" class="view2_phoneLead"><div class="ew-table-header-caption"><?php echo $view2_list->phoneLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phoneLead" class="<?php echo $view2_list->phoneLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->phoneLead) ?>', 1);"><div id="elh_view2_phoneLead" class="view2_phoneLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->phoneLead->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($view2_list->phoneLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->phoneLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->idSegmentLead->Visible) { // idSegmentLead ?>
	<?php if ($view2_list->SortUrl($view2_list->idSegmentLead) == "") { ?>
		<th data-name="idSegmentLead" class="<?php echo $view2_list->idSegmentLead->headerCellClass() ?>"><div id="elh_view2_idSegmentLead" class="view2_idSegmentLead"><div class="ew-table-header-caption"><?php echo $view2_list->idSegmentLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idSegmentLead" class="<?php echo $view2_list->idSegmentLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->idSegmentLead) ?>', 1);"><div id="elh_view2_idSegmentLead" class="view2_idSegmentLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->idSegmentLead->caption() ?></span><span class="ew-table-header-sort"><?php if ($view2_list->idSegmentLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->idSegmentLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->ufLead->Visible) { // ufLead ?>
	<?php if ($view2_list->SortUrl($view2_list->ufLead) == "") { ?>
		<th data-name="ufLead" class="<?php echo $view2_list->ufLead->headerCellClass() ?>"><div id="elh_view2_ufLead" class="view2_ufLead"><div class="ew-table-header-caption"><?php echo $view2_list->ufLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ufLead" class="<?php echo $view2_list->ufLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->ufLead) ?>', 1);"><div id="elh_view2_ufLead" class="view2_ufLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->ufLead->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($view2_list->ufLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->ufLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->cityLead->Visible) { // cityLead ?>
	<?php if ($view2_list->SortUrl($view2_list->cityLead) == "") { ?>
		<th data-name="cityLead" class="<?php echo $view2_list->cityLead->headerCellClass() ?>"><div id="elh_view2_cityLead" class="view2_cityLead"><div class="ew-table-header-caption"><?php echo $view2_list->cityLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cityLead" class="<?php echo $view2_list->cityLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->cityLead) ?>', 1);"><div id="elh_view2_cityLead" class="view2_cityLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->cityLead->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($view2_list->cityLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->cityLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->Tipo->Visible) { // Tipo ?>
	<?php if ($view2_list->SortUrl($view2_list->Tipo) == "") { ?>
		<th data-name="Tipo" class="<?php echo $view2_list->Tipo->headerCellClass() ?>"><div id="elh_view2_Tipo" class="view2_Tipo"><div class="ew-table-header-caption"><?php echo $view2_list->Tipo->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tipo" class="<?php echo $view2_list->Tipo->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->Tipo) ?>', 1);"><div id="elh_view2_Tipo" class="view2_Tipo">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->Tipo->caption() ?><?php echo $Language->phrase("SrchLegend") ?></span><span class="ew-table-header-sort"><?php if ($view2_list->Tipo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->Tipo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->extrasLead->Visible) { // extrasLead ?>
	<?php if ($view2_list->SortUrl($view2_list->extrasLead) == "") { ?>
		<th data-name="extrasLead" class="<?php echo $view2_list->extrasLead->headerCellClass() ?>"><div id="elh_view2_extrasLead" class="view2_extrasLead"><div class="ew-table-header-caption"><?php echo $view2_list->extrasLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="extrasLead" class="<?php echo $view2_list->extrasLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->extrasLead) ?>', 1);"><div id="elh_view2_extrasLead" class="view2_extrasLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->extrasLead->caption() ?></span><span class="ew-table-header-sort"><?php if ($view2_list->extrasLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->extrasLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view2_list->dataLead->Visible) { // dataLead ?>
	<?php if ($view2_list->SortUrl($view2_list->dataLead) == "") { ?>
		<th data-name="dataLead" class="<?php echo $view2_list->dataLead->headerCellClass() ?>"><div id="elh_view2_dataLead" class="view2_dataLead"><div class="ew-table-header-caption"><?php echo $view2_list->dataLead->caption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dataLead" class="<?php echo $view2_list->dataLead->headerCellClass() ?>"><div class="ew-pointer" onclick="ew.sort(event, '<?php echo $view2_list->SortUrl($view2_list->dataLead) ?>', 1);"><div id="elh_view2_dataLead" class="view2_dataLead">
			<div class="ew-table-header-btn"><span class="ew-table-header-caption"><?php echo $view2_list->dataLead->caption() ?></span><span class="ew-table-header-sort"><?php if ($view2_list->dataLead->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($view2_list->dataLead->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$view2_list->ListOptions->render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view2_list->ExportAll && $view2_list->isExport()) {
	$view2_list->StopRecord = $view2_list->TotalRecords;
} else {

	// Set the last record to display
	if ($view2_list->TotalRecords > $view2_list->StartRecord + $view2_list->DisplayRecords - 1)
		$view2_list->StopRecord = $view2_list->StartRecord + $view2_list->DisplayRecords - 1;
	else
		$view2_list->StopRecord = $view2_list->TotalRecords;
}
$view2_list->RecordCount = $view2_list->StartRecord - 1;
if ($view2_list->Recordset && !$view2_list->Recordset->EOF) {
	$view2_list->Recordset->moveFirst();
	$selectLimit = $view2_list->UseSelectLimit;
	if (!$selectLimit && $view2_list->StartRecord > 1)
		$view2_list->Recordset->move($view2_list->StartRecord - 1);
} elseif (!$view2->AllowAddDeleteRow && $view2_list->StopRecord == 0) {
	$view2_list->StopRecord = $view2->GridAddRowCount;
}

// Initialize aggregate
$view2->RowType = ROWTYPE_AGGREGATEINIT;
$view2->resetAttributes();
$view2_list->renderRow();
while ($view2_list->RecordCount < $view2_list->StopRecord) {
	$view2_list->RecordCount++;
	if ($view2_list->RecordCount >= $view2_list->StartRecord) {
		$view2_list->RowCount++;

		// Set up key count
		$view2_list->KeyCount = $view2_list->RowIndex;

		// Init row class and style
		$view2->resetAttributes();
		$view2->CssClass = "";
		if ($view2_list->isGridAdd()) {
		} else {
			$view2_list->loadRowValues($view2_list->Recordset); // Load row values
		}
		$view2->RowType = ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view2->RowAttrs->merge(["data-rowindex" => $view2_list->RowCount, "id" => "r" . $view2_list->RowCount . "_view2", "data-rowtype" => $view2->RowType]);

		// Render row
		$view2_list->renderRow();

		// Render list options
		$view2_list->renderListOptions();
?>
	<tr <?php echo $view2->rowAttributes() ?>>
<?php

// Render list options (body, left)
$view2_list->ListOptions->render("body", "left", $view2_list->RowCount);
?>
	<?php if ($view2_list->idLead->Visible) { // idLead ?>
		<td data-name="idLead" <?php echo $view2_list->idLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_idLead" class="view2_idLead">
<span<?php echo $view2_list->idLead->viewAttributes() ?>><?php echo $view2_list->idLead->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->emailLead->Visible) { // emailLead ?>
		<td data-name="emailLead" <?php echo $view2_list->emailLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_emailLead" class="view2_emailLead">
<span<?php echo $view2_list->emailLead->viewAttributes() ?>><?php echo $view2_list->emailLead->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->phoneLead->Visible) { // phoneLead ?>
		<td data-name="phoneLead" <?php echo $view2_list->phoneLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_phoneLead" class="view2_phoneLead">
<span<?php echo $view2_list->phoneLead->viewAttributes() ?>><?php echo $view2_list->phoneLead->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->idSegmentLead->Visible) { // idSegmentLead ?>
		<td data-name="idSegmentLead" <?php echo $view2_list->idSegmentLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_idSegmentLead" class="view2_idSegmentLead">
<span<?php echo $view2_list->idSegmentLead->viewAttributes() ?>><?php echo $view2_list->idSegmentLead->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->ufLead->Visible) { // ufLead ?>
		<td data-name="ufLead" <?php echo $view2_list->ufLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_ufLead" class="view2_ufLead">
<span<?php echo $view2_list->ufLead->viewAttributes() ?>><?php echo $view2_list->ufLead->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->cityLead->Visible) { // cityLead ?>
		<td data-name="cityLead" <?php echo $view2_list->cityLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_cityLead" class="view2_cityLead">
<span<?php echo $view2_list->cityLead->viewAttributes() ?>><?php echo $view2_list->cityLead->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->Tipo->Visible) { // Tipo ?>
		<td data-name="Tipo" <?php echo $view2_list->Tipo->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_Tipo" class="view2_Tipo">
<span<?php echo $view2_list->Tipo->viewAttributes() ?>><?php echo $view2_list->Tipo->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->extrasLead->Visible) { // extrasLead ?>
		<td data-name="extrasLead" <?php echo $view2_list->extrasLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_extrasLead" class="view2_extrasLead">
<span<?php echo $view2_list->extrasLead->viewAttributes() ?>><span style="white-space: pre-wrap;"><?php echo $view2_list->extrasLead->getViewValue() ?></span></span>
</span>
</td>
	<?php } ?>
	<?php if ($view2_list->dataLead->Visible) { // dataLead ?>
		<td data-name="dataLead" <?php echo $view2_list->dataLead->cellAttributes() ?>>
<span id="el<?php echo $view2_list->RowCount ?>_view2_dataLead" class="view2_dataLead">
<span<?php echo $view2_list->dataLead->viewAttributes() ?>><?php echo $view2_list->dataLead->getViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view2_list->ListOptions->render("body", "right", $view2_list->RowCount);
?>
	</tr>
<?php
	}
	if (!$view2_list->isGridAdd())
		$view2_list->Recordset->moveNext();
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$view2->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php

// Close recordset
if ($view2_list->Recordset)
	$view2_list->Recordset->Close();
?>
<?php if (!$view2_list->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$view2_list->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php echo $view2_list->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $view2_list->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($view2_list->TotalRecords == 0 && !$view2->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $view2_list->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$view2_list->showPageFooter();
if (Config("DEBUG"))
	echo GetDebugMessage();
?>
<?php if (!$view2_list->isExport()) { ?>
<script>
loadjs.ready("load", function() {

	// Startup script
	// Write your table-specific startup script here
	// console.log("page loaded");

});
</script>
<?php if (!$view2->isExport()) { ?>
<script>
loadjs.ready("load", function() {
	var $ = jQuery;
	$(".ew-grid-middle-panel").removeClass(ew.RESPONSIVE_TABLE_CLASS); // Disable responsive table
	$(".wrapper").css("height", "100vh").overlayScrollbars({
		callbacks: {
			onOverflowChanged: function(eventArgs) {
				$(document).trigger("overflow", [eventArgs]); // Trigger "overflow" event
			}
		}
	});
});
</script>
<?php } ?>
<?php } ?>
<?php include_once "footer.php"; ?>
<?php
$view2_list->terminate();
?>