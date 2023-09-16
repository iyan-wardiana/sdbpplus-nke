<!------------------------------------------------------------------------------
APPLICATION......: Polychemerp
FID..............: -
FUID/SELACTIONID.: -
FILENAME.........: add.cfm
================================================================================
CREATED BY.......: - Dian Hermanto
CREATED DATE.....: - 19 Maret 2014
================================================================================
DESCRIPTION......:  Add Multiple Shipping Instruction 
================================================================================
REVISION.........: 
------------------------------------------------------------------------------->

<cfoutput>
<cfif task eq "save">
	<cfset varSecAccess = REQUEST.SFSecAccess.SecAccessFile(FILEACCESSCODE="ERSTD0862408", BACKURL="#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/index.cfm?selListItem=1&menu=0")>
<cfelse>
	<cfset varSecAccess = REQUEST.SFSecAccess.SecAccessFile(FILEACCESSCODE="ERSTD0862404", BACKURL="#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/index.cfm?selListItem=1&menu=0")>
</cfif>

<cfset LANGUAGELIST = "ShippingInstruction,Sales,ShippingInstruction_Number,ItemCategory,Asset,RawMaterial,FinishedGood,SparePart,WIP,DateFrom,DateTo,Page,Of,SEARCH">
<cfset LANGUAGELIST = #LANGUAGELIST# & ",CreateShippingInstruction,DateReq,eHRMAltDate,EdDateMustGTStDate,ShippingInstruction_Date,Doc_Status,Shipping_Status,Ref_Number,CustomerName,SOType,Local,Export">
<cfset LANGUAGELIST = #LANGUAGELIST# & ",Add,Edit,AllowedToleranceDifference,Customer,Description,CustomerPO,eHRMSave,eHRMConfirm,OtherDoc,Remaining,CreatedBy,creationdate,Update,By,eHRMLastUpdate">
<cfset LANGUAGELIST = #LANGUAGELIST# & ",ItemCode,Dimension,None,SOQty,ShippingQty,Update,UnitType,WH,BinName,EstimateDate,Remark,SelectBin,Hold,Release,CancelShipping,Back,Lot,ShipTo,ConfirmAndAddOthers,SplitRelease,remainNow,ShippingQtyNow,AddMultiple">
<CF_DO_V25_MULTILANGUAGE MESSAGEIDLIST="#LANGUAGELIST#">
 
<head><title>#DO_VAR['ShippingInstruction']# | <cfif isDefined("url.InsNum")>#DO_VAR['Edit']#<cfelse>#DO_VAR['Add']#</cfif></title></head>
<cfset brs = 1>

<script language="javascript" src="#Application.stApp.Web_Path[vst_idx]#/include/js/allscripts.js"></script>
<script language="javascript" src="#Application.stApp.Web_Path[vst_idx]#/include/js/ajax_engine.js"></script>
<cfinclude template="#Application.stApp.CFWeb_Path[1]#/include/lockperiod/checklock.cfm">
<cfinclude template="#Application.stApp.CFWeb_Path[1]##Application.stApp.SPT[VST_IDX]#include#Application.stApp.SPT[VST_IDX]#calendar#Application.stApp.SPT[VST_IDX]#sunfish_calendar.cfm">
<cfinclude template="#Application.stApp.CFWeb_Path[1]##Application.stApp.SPT[VST_IDX]##Application.stApp.Home_Url[vst_idx]##Application.stApp.SPT[VST_IDX]#include#Application.stApp.SPT[VST_IDX]#validatedatefromto.cfm">
<script language="javascript" type="text/javascript" src="#Application.stApp.Web_Path[VST_IDX]#/include/js/overlib_mini.js"></script>
<script language="javascript" type="text/javascript" src="#Application.stApp.Web_Path[VST_IDX]#/include/js/overlib_hideform_mini.js"></script>

<!--- Add by DH on March, 19 2014. Shipping Instruction Detail --->
<cfparam name="dcf_Identity" default="0">
<!--- End Add by DH on March, 19 2014. Shipping Instruction Detail --->

<cfparam name="selRefNum" default="">
<cfparam name="rowSplit" default="1">

<cfif task eq "Save">
    <cfquery name="qGetSO" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
        Select * from TAccSO_Header
        Join TAccSO_Detail on TAccSO_Header.SO_Number = TAccSO_Detail.SO_Number
        Join TAccount on TAccount.Account_Id = TAccSO_Header.Account_Id
        Where TAccSO_Header.SO_Number = '#SELREFNUM#'
        and TAccSO_Header.ItemCategoryType = '#CBOTYPE#'
		and TAccSO_Header.SO_Status = 3 
		AND TAccSO_Header.Approval_Status = 3     
		<!---wx : hardcode selain EG Tolling yg tampil di shipping instruction--->
		AND TaccSO_Header.SalesType_Code NOT IN ('EG_TOLLING')
        <!---JF : HANYA TAMPILKAN SO YANG BELUM FULL DELIVERED--->
        AND TACCSO_HEADER.SN_STATUS <> 'FD'
        <!---END JF--->
    </cfquery>
    <cfif qGetSO.recordcount>
        <cfparam name="rdoExport" default="#qGetSO.isExport#">
    <cfelse>
        <cfparam name="rdoExport" default="0">
    </cfif>
</cfif>

<cfset vartemplate = "index.cfm">
<cfif task eq "save">
	<cfset varquerystring = "?FID=ERSTD08624&FUID=ERSTD0862408&menu=1">	
<cfelse>
	<cfset varquerystring = "?FID=ERSTD08624&FUID=ERSTD0862404&menu=1&InsNum=#txtInsNo#">	
</cfif>

<body onUnload="doCloseChild(arrNewPop)">
<form method="post" name="frmNew" action="#Application.Stapp.Web_Path[vst_idx]#/#Application.Stapp.Home_url[vst_idx]#/#vartemplate##varquerystring#&task=#task#">
<INPUT type="Hidden" name="task" 	value="#task#">
<INPUT type="Hidden" name="CBOTYPE" value="#CBOTYPE#">

<table width="100%" class="formtitle" border="0">
    <tr align="left">
        <td colspan="2"> 
            <img src="#Application.stApp.Upload_Path[1]#/doadminsite/penjualan.gif" width="18" height="18" border="0" alt="">
            #DO_VAR['Sales']# | #DO_VAR['ShippingInstruction']# | <cfif isDefined("url.InvNum")>#DO_VAR['Edit']#<cfelse>#DO_VAR['AddMultiple']#</cfif>
        </td>
    </tr>
    <tr height="20">
    	<td>
    		<table class="formbody" width="100%" border="0" cellpadding="2" cellspacing="0">
                <tr height="20">
        			<td class="formtext" valign="top">#DO_VAR["ShippingInstruction_Number"]#</td>
                    <td class="formtext" valign="top">:</td>
        			<td class="formtext" valign="top">
                        <cfif task eq "save">
        					
                            <CF_DO_V30_ACCDOCUMENTNO TableName="TAccPattern" DocumentType="ShippingInstruction" DocumentNo="ShipInsNum" Type="Pattern" Companyid="#cookie.companyid#" isTax="Tax" LocationID="#COOKIE.LOCATION_ID#">
                            <cfset txtInsNo = ShipInsNum>
        					<strong>#txtInsNo#</strong>
                            <input type="hidden" name="txtInsNox" id="txtInsNox" value="#txtInsNo#">
        				<cfelse>
        					#txtInsNo#
                            <cfset InsNumber = #txtInsNo#>
        					<input type="Hidden" name="txtInsNo" id="txtInsNo" value="#txtInsNo#">
        					<cfset InsNumber = #txtInsNo#>
        					<input type="Hidden" name="InsNum" id="InsNum" value="#txtInsNo#">
                            
                            <cfquery name="qGetDetail" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                                Select TAccount.account_name,TAccShippingInstruction_Header.*, TAccShippingInstruction_Detail.WH_TYPE_ID,
                                TaccSO_Header.SalesType_Code,TaccSO_Header.SOType,TaccSO_Header.isExport from TAccShippingInstruction_Header
                                Join TAccShippingInstruction_Detail on 
                                    TAccShippingInstruction_Header.ShippingInstruction_Number= TAccShippingInstruction_Detail.ShippingInstruction_Number
                                Join TAccount on TAccount.Account_Id = TAccShippingInstruction_Header.Account_Id
                                And TAccShippingInstruction_Header.ShippingInstruction_Number = '#txtInsNo#'
								JOIN TaccSO_Header ON TaccSO_Header.SO_Number = TAccShippingInstruction_Header.Ref_Number
                            </cfquery>
        				</cfif>			
                    </td>
                    <td class="formtext" valign="top">#DO_VAR["ShippingInstruction_Date"]#</td>
                    <td class="formtext" valign="top">:</td>
        			<td class="formtext" valign="top">
                       <cfif task eq "save">
                            <cfparam name="txtInsDate" default="#dateFormat (now (), 'mm/dd/yyyy')#">
                       <cfelse>
                            <cfparam name="txtInsDate" default="#dateFormat (qGetDetail.ShippingInstruction_Date, 'mm/dd/yyyy')#">
                       </cfif>
                       <script type="text/javascript">SunFishERP_DateTimePicker('txtInsDate','#txtInsDate#');</script>
                       <cfset dcf_Identity = dcf_Identity + 1>	
                    </td>
                </tr>
				<tr>
					<td class="formtext" valign="top">#DO_VAR["SOType"]#</td>
                    <td class="formtext" valign="top">:</td>
                    <td class="formtext" valign="top">                        
                        <cfif task eq "Save">
                            <input type="radio" name="rdoExport" value="0" onClick="refresh();" <cfif rdoExport eq 0>checked</cfif>>#DO_VAR["Local"]#
							<input type="radio" name="rdoExport" value="1" onClick="refresh();" <cfif rdoExport eq 1>checked</cfif>>#DO_VAR["Export"]#
                        <cfelse>
                           <cfif qGetDetail.isExport eq 1>#DO_VAR["Export"]#<cfelse>#DO_VAR["Local"]#</cfif>
                        </cfif>
                    </td>
                    <td colspan="3" valign="top">&nbsp;</td>
				</tr>
                <tr>
                    <td class="formtext" valign="top">#DO_VAR["Ref_Number"]#</td>
                    <td class="formtext" valign="top">:</td>
                    <td class="formtext" valign="top">
                        <cfif task eq "Save">
                            <DIV id="DivRefNum">
                                <INPUT name="selRefNum" id="selRefNum" type="text" 
                                onKeyUp="switched('RefNum',this)" size="25" 
                                maxlength="25" onClick="switched('RefNum',this)" 
                                value="#selRefNum#">
                                <a style="cursor:pointer" onClick="setObjField('selRefNum','divAjaxLookupRefNum'); onEvent();" title="GO">
                                    <IMG src="#Application.stApp.Web_Path[1]#/images/quicksearch.jpg" 
                                    alt="#DO_VAR['SEARCH']#" border="0" width="18" height="18" />
                                </a>
                                <br>
                                <DIV id="divAjaxLookupRefNum" style="width: 370px; height: 180px; position: absolute; display: none; 
                                border: 2px solid black; background-color: white; z-index: 1000">
                                </DIV>
                            </DIV>
                        <cfelse>
                            #qGetDetail.Ref_Number#
                            <input type="hidden" name="selRefNum" id="selRefNum" value="#qGetDetail.Ref_Number#">
                        </cfif>
                    </td>
                    <td class="formtext" valign="top">#DO_VAR["AllowedToleranceDifference"]# (%)</td>
                    <td class="formtext" valign="top">:</td>
        			<td class="formtext" valign="top">
                       <cfif task eq "save">
                            <input type="Text" name="txtTolerance" style="width:50px;text-align:right;" value="#NumberFormat(qGetSO.tolerance_percentage,",.#repeatstring("_",Application.stApp.decimaL_range[VST_IDX])#")#">
                       <cfelse>
                            <input type="Text" name="txtTolerance" style="width:50px;text-align:right;" value="#NumberFormat(qGetDetail.tolerance_percentage,",.#repeatstring("_",Application.stApp.decimaL_range[VST_IDX])#")#">
                       </cfif>
                    </td>
                </tr>
                <tr>
                    <td class="formtext" valign="top">#DO_VAR["Customer"]#</td>
                    <td class="formtext" valign="top">:</td>
                    <td class="formtext" valign="top">
                       <cfif task eq "save">
                           #qGetSO.Account_Name#
                           <input type="hidden" name="txtAccountId" value="#qGetSO.account_Id#">
                       <cfelse>
                           #qGetDetail.Account_Name#
                           <input type="hidden" name="txtAccountId" value="#qGetDetail.account_Id#">
                       </cfif>
                    </td>
                    <td class="formtext" valign="top">#DO_VAR["Description"]#</td>
                    <td class="formtext" valign="top">:</td>
                    <td class="formtext" valign="top">                        
                       <textarea name="txtNotes" rows="4" style="width:300px"><cfif task neq "save">#qGetDetail.Description#</cfif></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="formtext" valign="top">#DO_VAR["CustomerPO"]#</td>
                    <td class="formtext" valign="top">:</td>
                    <td class="formtext" valign="top">
                    <cfif task eq "save">
                        <input type="text" name="txtPONum" value="#qGetSO.PO_NumCustomer#">
                    <cfelse>
                        <input type="text" name="txtPONum" value="#qGetDetail.PO_NumCustomer#">
                    </cfif>
                    </td>
                    <td class="formtext" colspan="3" valign="top">&nbsp;</td>                   
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                
                <!---nath. 20140317. menambah form add new multiple SIN--->
                <tr>
                    <td style="border:3px double black;" colspan="6">
                        <table width="100%" id="tblDO1" class="formtext" cellpadding="2" border="0" cellspacing="1">
                            <tr>
            					<td align="center" class="formtitle"> 
            						<input type="Checkbox" onClick="IsSelectAll_1(this)" name="chkAll_1" id="chkAll_1">
            					</td>
            					<td align="center" class="formtitle">#DO_VAR["ItemCode"]#</td>
            					<td align="center" class="formtitle">#DO_VAR["Description"]#</td>
                                <td align="center" class="formtitle">#DO_VAR["Dimension"]#</td>
                                <td align="center" class="formtitle">#DO_VAR["SOQty"]#</td>
                                <td align="center" class="formtitle">#DO_VAR["OtherDoc"]#</td>
                                <td align="center" class="formtitle">#DO_VAR["Remaining"]#</td>
            					<td align="center" class="formtitle" >#DO_VAR["ShippingQtyNow"]#</td>
                                <td align="center" class="formtitle" >#DO_VAR["RemainNow"]#</td>
            					<td align="center" class="formtitle" >#DO_VAR["UnitType"]#</td>
                            </tr>
                            <cfif task eq "save" and isdefined('SELREFNUM') and SELREFNUM neq ''>
                            
                                <cfquery name="qItem" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                                             SELECT TAccSO_Detail.SODetail_ID AS Detail_ID,
                                                    TAccSO_Detail.Item_Code,
                                                    TItem.Item_name,
                                                    TAccSO_Detail.QTY,
                                                    Titem.isLabel,
                                                    TItem.labelout,
                                                    TAccSO_Detail.generate_Flag,
                                                    TItem.habis,
                                                    tItem.OutgoingInspectionCustom_id,
                                                    tItem.OutgoingInspectionStandard_id,
                                                    EstimateDate,
                                                    TAccSO_Detail.QTY as Qty2,
                                                    TAccSO_Detail.Parent_Item,
                                                    TAccSO_Detail.Parent_Path,
                                                    TAccSO_Detail.Config_Level,
                                                    TAccSO_Detail.Config_Ratio,
                                                    TAccSO_Detail.Config_Order, 
                                                    TAccSO_Detail.Dimension_ID,
                                                    ISNULL(itd.Dimension_Name, '') AS Dimension_Name, 
                                                    UPPER(TItem.CostingMethod) AS CostingMethod 
                                    FROM			TAccSO_Detail
                                    INNER JOIN	TItem ON	TACCSO_Detail.Item_Code = TItem.ITem_Code 
                                    INNER JOIN 	TACCSO_HEADER ON TACCSO_DETAIL.SO_NUMBER = TACCSO_HEADER.SO_NUMBER
                                    LEFT JOIN TITEMDIMENSION itd ON itd.Dimension_ID = TAccSO_Detail.Dimension_ID 
                                    WHERE 			TAccSO_Detail.SO_number  = '#SELREFNUM#'
                                    	AND TACCSO_HEADER.SN_STATUS <> 'FD'
										AND	TACCSO_HEADER.CREATED_BY = #COOKIE.CKSATRIADEVID#
                                    ORDER BY 		TAccSO_Detail.SODEtail_ID
                                </cfquery>
                            <cfelse>
                                <cfquery name="qItem" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                                    SELECT TAccShippingInstruction_Detail.Detail_ID,
                                            TAccShippingInstruction_Detail.Item_Code,
                                            TItem.Item_name,
                                            TAccSO_Detail.QTY as Qty2,
                                            TAccShippingInstruction_Detail.QTY,
											Case When TAccShippingInstruction_Detail.Hold_Qty is NULL
											Then
												TAccShippingInstruction_Detail.Shipping_QTY
											Else
												TAccShippingInstruction_Detail.Qty
											End
											as Shipping_QTY,                                            
                                            TAccShippingInstruction_Detail.Estimate_date,
                                            TAccShippingInstruction_Detail.WH_ID,
                                            TAccShippingInstruction_Detail.Notes,
                                            TAccShippingInstruction_Detail.Lot,
                                            TAccShippingInstruction_Detail.WH_Type_ID,
                                            Titem.isLabel,
                                            TItem.labelout,
                                            TItem.habis,
                                            TAccSO_Detail.Parent_Item,
                                            TAccSO_Detail.Parent_Path,
                                            TAccSO_Detail.Config_Level,
                                            TAccSO_Detail.Config_Ratio,
                                            TAccSO_Detail.Config_Order, 
                                            tItem.OutgoingInspectionCustom_id,
                                            tItem.OutgoingInspectionStandard_id,
                                            TAccShippingInstruction_Detail.Dimension_ID,
                                            ISNULL(itd.Dimension_Name, '') AS Dimension_Name, 
                                            UPPER(TItem.CostingMethod) AS CostingMethod 
									FROM TAccShippingInstruction_Detail
                                            JOIN TAccShippingInstruction_Header 
                                                ON TAccShippingInstruction_Header.ShippingInstruction_Number = TAccShippingInstruction_Detail.ShippingInstruction_Number
                                            JOIN TItem 
                                                ON TAccShippingInstruction_Detail.Item_Code = TItem.ITem_Code 
                                            LEFT JOIN TITEMDIMENSION itd 
                                                ON itd.Dimension_ID = TAccShippingInstruction_Detail.Dimension_ID
                                            JOIN TAccSO_Detail on  TAccSO_Detail.SO_Number= TAccShippingInstruction_Header.Ref_Number
                                                And TAccSO_Detail.Item_Code = TAccShippingInstruction_Detail.Item_Code
                                    WHERE 			TAccShippingInstruction_Detail.ShippingInstruction_Number = '#txtInsNo#'                  
                                    ORDER BY 		TAccShippingInstruction_Detail.DEtail_ID
                                </cfquery>
                            </cfif>                            
                           
                            <cfset lstItem="'" & #ReplaceNoCase(ValueList(qITem.Item_Code),",","','","ALL")# & "'">
                            
                            <cfquery name="qUnitType" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                            	SELECT		TItem.Unit_Type_ID, Unit_Name,Item_Code
                            	FROM		TACCUnitType
                            	INNER JOIN 	TItem ON TACCUnitType.Unit_Type_ID =TItem.Unit_Type_ID
                            	WHERE		Item_Code IN (#PreserveSingleQuotes(lstItem)#)
                            	ORDER BY	Item_Code
                            </cfquery>
                            <cfset brs1 = 1>
							
							<!--- IND, split release only checked items --->
							<cfset duplicableItem = "">
							<cfif isDefined("CHK1") and ListLen("chk1")>
								<cfloop list="#chk1#" index="checkedItem">
									<cfif isDefined("CHKITEMHID#checkedItem#")>
											<cfset duplicableItem = listAppend(duplicableItem,Evaluate("CHKITEMHID#checkedItem#"))>
									</cfif>
								</cfloop>
							</cfif>
							
							
                            <cfparam name="on_off" default=1>
                            <cfloop from="1" to="#rowSplit#" index="idxRow">
                            	<cfset on_off=not on_off>
                            	<cfif on_off>
                                	<cfset class = "TablebodyEven">
                                 <cfelse>
                                 	<cfset class = "TablebodyOdd">
                                </cfif>
                            <cfloop query="qItem">
							
								<!--- IND, split release only checked items --->
								<cfset duplicateThisItem = true>
								<cfif idxRow NEQ 1>
									<cfif NOT listFindNoCase(duplicableItem,qItem.item_code)>
										<cfset duplicateThisItem = false>
									</cfif>
								</cfif>
								
								<cfif duplicateThisItem>
								
								<!--- END IND split release only checked items --->
                                <cfquery name="qEverDeliver" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                                    SELECT 		TAccShippingInstruction_Detail.Item_Code, TAccShippingInstruction_Detail.Dimension_ID, 
                                    <!--- Sum(ISNULL(Shipping_QTy, 0)) as jml --->  <!---Sum(ISNULL(QTy, 0)) as jml--->
                                    			CASE WHEN Shipping_Status = 'HD' and  isNull(isclose,0) = 1 THEN
                                                    SUM(ISNULL(TAccSN_Item.QTy, 0)) 
                                                ELSE
                                                    SUM(ISNULL(TAccShippingInstruction_Detail.QTy, 0))
                                                END AS jml
                                    FROM 		TAccShippingInstruction_Detail
                                    INNER JOIN	TAccShippingInstruction_Header	ON TAccShippingInstruction_Detail.ShippingInstruction_Number = TAccShippingInstruction_Header.ShippingInstruction_Number
                                    LEFT JOIN	TAccSN_Header ON TAccSN_Header.ShippingInstruction_Number = TAccShippingInstruction_Header.ShippingInstruction_Number
                                                AND ISNULL(TAccSN_Header.isVoid,0) <> 1
                                                AND ISNULL(TAccSN_Header.Approval_Status,0) <> 4
                                    LEFT JOIN	TAccSN_Item ON TAccSN_Item.SN_NUMBER = TAccSN_Header.SN_Number 
                                                AND TAccSN_Item.Item_Code = TAccShippingInstruction_Detail.Item_Code
                                                AND TAccSN_Item.Dimension_ID = TAccShippingInstruction_Detail.Dimension_ID
                                    <cfif task eq "Save">
                                    WHERE		TAccShippingInstruction_Header.Ref_number= '#SELREFNUM#'     
                                    <cfelse>
                                    WHERE		TAccShippingInstruction_Header.Ref_number= '#qGetDetail.Ref_Number#'
                                    </cfif>                                    
                                    AND isNull(TAccShippingInstruction_Header.Doc_Status,0) <> 4 <!--- and isNull(TAccShippingInstruction_Header.Doc_Status,0) >= 1 0=save, 1=confirm, 2=hold, 3=release, 4=cancel --->
                                    AND TAccShippingInstruction_Header.ShippingInstruction_Number not in ('#txtInsNo#')
									AND (isNull(isclose,0) = 0 or (isNull(isclose,0) = 1 and (Shipping_Status = 'HD' or Shipping_Status = 'FD'))) <!---AND isNull(isclose,0) = 0 MMY, 03 Okt 2012--->
                                    AND TAccShippingInstruction_Detail.Item_Code= '#Item_Code#'
                                    GROUP BY 	TAccShippingInstruction_Detail.Item_Code,  TAccShippingInstruction_Detail.Dimension_ID, Shipping_Status, isclose
                                    ORDER BY 	TAccShippingInstruction_Detail.Item_Code,  TAccShippingInstruction_Detail.Dimension_ID
                                </cfquery>
                                <tr class="#class#">
                                    <td align="center" class="formtext">
                                        <input type="checkbox" name="chk1#brs1#" id="chk1#brs1#" onClick="tickItem1(this,#brs1#)" value="#brs1#">
                                        <input type="Hidden" name="txtcostingmethod_#brs1#" id="txtcostingmethod_#brs1#" value="#costingmethod#">
                                        <input type="Hidden" name="hidHabis#brs1#" id="hidHabis#brs1#" value="#qItem.habis#">
                                    </td>
                                    <td align="center" class="formtext">
                                        #Item_Code#
                                        <input type="Hidden" name="chkItemHid#brs1#" id="chkItemHid#brs1#" value="#item_code#">
                                        <input type="Hidden" name="myItem_Name#brs1#" id="myItem_Name#brs1#" value="#Item_Name#">
                                    </td>
                					<td align="center" class="formtext">#Item_Name#</td>
                                    <td align="center" class="formtext">
                                        <input type="text" name="txtDimensionName_#brs1#" id="txtDimensionName_#brs1#" value="#HTMLEDITFORMAT(Dimension_Name)#" class="inplabel" readonly />
                                        <input type="hidden" name="txtDimensionID_#brs1#" id="txtDimensionID_#brs1#" value="#Dimension_ID#" />
                                    </td>
                                    <td align="center" class="formtext">
                                        #NumberFormat(val(Qty2),",.#repeatstring("_",Application.stApp.decimaL_range[VST_IDX])#")#
                                        <input type="hidden" name="txtSOQty_#brs1#" id="txtSOQty_#brs1#" value="#val(Qty2)#">
                                    </td>
                                    <script>
                                        function showOtherDoc(item) {
                                            <cfif task eq "Save">
                                                var refnum = "#SELREFNUM#";
                                            <cfelse>
                                                var refnum = "#qGetDetail.Ref_Number#";
                                            </cfif>    
                                            <cfset varTemplate = "index.cfm">
					                        <cfset varQueryString= "?FID=ERSTD08624&FUID=ERSTD0862406&menu=1">
                                            window.open('#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/#varTemplate##varQueryString#&txtInsNo=#txtInsNo#&item_code='+item+'&SELREFNUM='+refnum+'&isPopUp=yes','Detail','width=700, height=400, scrollbars=yes,status=yes,resizable=yes');
                                        }
                                    </script>
                                    <td align="center" class="formtext">
                                        <a href="javascript:showOtherDoc('#qItem.Item_Code#');">
                                            #NumberFormat(val(qEverDeliver.jml),",.#repeatstring("_",Application.stApp.decimaL_range[VST_IDX])#")#
                                        </a>                                        
                                        <input type="hidden" name="txtDeliveredQty_#brs1#" id="txtDeliveredQty_#brs1#" value="#val(qEverDeliver.jml)#">
                                    </td>
                                    <td align="center" class="formtext">
                                        #NumberFormat((val(Qty2) - val(qEverDeliver.jml)),",.#repeatstring("_",Application.stApp.decimaL_range[VST_IDX])#")#
                                        <input type="hidden" name="txtRemainQty_#brs1#" id="txtRemainQty_#brs1#" value="#(val(Qty2) - val(qEverDeliver.jml))#">
                                    </td>
                					<td align="center" class="formtext">
                                        <input type="text" name="txtShipQty_#brs1#" id="txtShipQty_#brs1#" 
                                        <cfif isdefined("txtShipQty_#brs1#")>
                                            value="#evaluate('txtShipQty_#brs1#')#"
                                        <cfelseif task neq "Save">
                                            value="#qItem.Shipping_Qty#"
                                        <cfelse>
                                            value="#NumberFormat(0,",.#repeatstring("_",Application.stApp.decimaL_range[VST_IDX])#")#"
                                        </cfif>
                                        <!---<cfif isdefined("qGetDetail") and qGetDetail.Doc_Status gt 0>--->
                                        readonly
                                        <!---</cfif>--->
                                        onchange="refreshBin('#brs1#','#qItem.habis#','#qItem.qty2#','#Item_Code#');"
                                        > 
                                    </td>
                                    <td align="center" class="formtext">
                                        #NumberFormat((val(Qty2) - val(qEverDeliver.jml)),",.#repeatstring("_",Application.stApp.decimaL_range[VST_IDX])#")#
                                        <input type="hidden" name="txtRemainQty_#brs1#" id="txtRemainQty_#brs1#" value="#(val(Qty2) - val(qEverDeliver.jml))#">
                                    </td>
                					<td align="center" class="formtext">
                                    <cfset intUnitTypeID = 0>
        							<cfset idx = ListFindNocase(ValueList(qUnitType.Item_Code),Item_Code)>
        							<cfif idx gt 0>
        								<cfset intUnitTypeID = #qUnitType.Unit_Type_ID[idx]#>
        								#qUnitType.Unit_Name[idx]#
                                        <!--- Add by DH on March, 19 2014 --->
        								<input type="hidden" name="txtMeasureUnitName_#brs1#" id="txtMeasureUnitName_#brs1#" value="#qUnitType.Unit_Name[idx]#">
                                        <!--- End Add by DH on March, 19 2014 --->
        								<input type="hidden" name="txtMeasureUnit_#brs1#" id="txtMeasureUnit_#brs1#" value="#qUnitType.Unit_Type_ID[idx]#">
        							<cfelse>
        								<input type="hidden" name="txtMeasureUnit_#brs1#" id="txtMeasureUnit_#brs1#" value="0">
        							</cfif>
                                    </td>
                                   
                                    <cfparam name="DONumber" default="">
                                                                  
                                    </td>
                                </tr>
                                
                                <cfset brs1 = brs1+1>
								
								</cfif><!--- IND, duplicateThisItem Condition --->
								
                            </cfloop>
                            </cfloop>
                        </table>
                    </td>
                </tr>
                
               
               	<tr>
                    <td>&nbsp;
                    	
                    </td>
                </tr>
                
                <tr>
                    <td colspan="6">
                    	<a href="javascript:void(null);" onClick="TambahItem();">[+ <em>Add Shipping Instruction</em>]</a> <a href="javascript:void(null);" onClick="DeleteItem();">[- Delete]</a>
                    </td>
                </tr>
                
                <!--- Add by DH on March, 18 2014. Shipping Instruction Detail --->
                	<cfquery name="qWHLocation" datasource="#iif(isdefined('DSN'),'DSN','ATTRIBUTES.DSN')#">
                         Select WH_Type_ID as varWHID, WH_Type_Name as WH_Name from TAccWHType
                    </cfquery>
                    
                    <cfif task eq "save">
                        <cfparam name="txtEstDate_#brs#" default="#DateFormat(now(),"mm/dd/yyyy")#">
                    <cfelse>
                        <cfparam name="txtEstDate_#brs#" default="#DateFormat(qItem.Estimate_Date,"mm/dd/yyyy")#">
                    </cfif>
                                                      
                	<input type="hidden" name="totalrowdefault" id="totalrowdefault" value="#qitem.recordcount#">
					<script>
                        function TambahItem()
                        {
                            var myCheck = document.getElementById('totalrowdefault').value;
							var myHigerValue = document.getElementById('myHigerValue').value; 
							if(myHigerValue==0)
							intIndexHeader = parseFloat(myHigerValue);
							else
							intIndexHeader = parseFloat(myHigerValue) + parseFloat(1);
							//alert(intIndexHeader)
							
                            checkeditem = 0;
                            for(idx=1;idx<=myCheck;idx++)
                            {
                                if(document.getElementById('chk1'+idx).checked==true)
                                {
                                    var checkeditem = checkeditem + 1;
                                }
                            }
                            if(checkeditem == 0)
                            {
                                alert('Please Choose Item')
                                return false;
                            }
                            document.getElementById('trTabelDetail').style.display = '';
                            var myTotIndex = 0;					
							
							var objTable, objTR, objTD, intIndexHeader;
							objTable = document.getElementById('tblNewDetailShip');
							intIndexHeader1 = objTable.rows.length;
							objTR = objTable.insertRow(intIndexHeader1);
							objTR.id = 'tr_' + intIndexHeader1;
							
							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '<input type="Checkbox" onClick="CheckHeaderTable('+intIndexHeader+')" name="chkAll2_'+intIndexHeader+'" id="chkAll2_'+intIndexHeader+'" value="'+intIndexHeader+'" checked><input style="display:none" type="checkbox" name="chk2'+intIndexHeader+'" id="chk2'+intIndexHeader+'" value="'+intIndexHeader+'" checked /><input type="hidden" name="isChecked'+intIndexHeader+'" id="isChecked'+intIndexHeader+'" value="yes" size="2" /><input type="hidden" name="ParentIndex'+intIndexHeader+'" name="ParentIndex'+intIndexHeader+'" class="inplabel" size="4" style="text-align:right;" value="'+intIndexHeader+'" /><input type="hidden" name="groupIndex'+intIndexHeader+'" size="2" id="groupIndex'+intIndexHeader+'" value="'+intIndexHeader+'" /><input type="hidden" name="isHeader'+intIndexHeader+'" id="isHeader'+intIndexHeader+'" value="1" size="2" />';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["ItemCode"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["Description"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["Dimension"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["SOQty"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["OtherDoc"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["Remaining"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["ShippingQty"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["UnitType"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["WH"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["Remark"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["EstimateDate"]#';

							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.className = 'formtitle';
							objTD.innerHTML = '#DO_VAR["ShipTo"]#';
							
							intIndexDet = parseFloat(intIndexHeader);
                            for(idx=1;idx<=myCheck;idx++)
                            {
                                if(document.getElementById('chk1'+idx).checked==true)
                                {
                                    var myMethod = document.getElementById('txtcostingmethod_'+idx).value;
                                    var myidHabis = document.getElementById('hidHabis'+idx).value;
                                    var myItmeCode = document.getElementById('chkItemHid'+idx).value;
                                    var myItmeName = document.getElementById('myItem_Name'+idx).value;
                                    var myDimensionID = document.getElementById('txtDimensionID_'+idx).value;
                                    var myDimensionName = document.getElementById('txtDimensionName_'+idx).value;
                                    var mySOQty1 = document.getElementById('txtSOQty_'+idx).value;
                                    var mySOQty = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(mySOQty1)),#Application.stApp.decimaL_range[VST_IDX]#));
                                    var myDeliveredQty1 = document.getElementById('txtDeliveredQty_'+idx).value;
                                    var myDeliveredQty = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(myDeliveredQty1)),#Application.stApp.decimaL_range[VST_IDX]#));
                                    var myRemainQty1 = document.getElementById('txtRemainQty_'+idx).value;
                                    var myRemainQty = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(myRemainQty1)),#Application.stApp.decimaL_range[VST_IDX]#));
                                    var myShipQty1 = document.getElementById('txtShipQty_'+idx).value;
                                    var myShipQty = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(myShipQty1)),#Application.stApp.decimaL_range[VST_IDX]#));
                                    var myMeasUnit = document.getElementById('txtMeasureUnit_'+idx).value;
                                    var txtMeasureUnit_ = document.getElementById('txtMeasureUnit_'+idx).value;
                                    var myMeasUnitName = document.getElementById('txtMeasureUnitName_'+idx).value;
                                    
                                    var objTable, objTR, objTD, intIndex;
                                    objTable = document.getElementById('tblNewDetailShip');
									intIndexDet1 = objTable.rows.length;
                                    objTRDet = objTable.insertRow(intIndexDet1);
                                    objTR.id = 'tr_' + intIndexDet1;
									
									intIndexDet = intIndexDet + parseFloat(1);
									
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = '<input type="checkbox" name="chk2'+intIndexDet+'" id="chk2'+intIndexDet+'" value="'+intIndexDet+'" onclick="CheckDetailTable(this,'+intIndexDet+')" checked /><input type="hidden" name="isHeader'+intIndexDet+'" id="isHeader'+intIndexDet+'" value="0" size="2" /><input type="hidden" name="isChecked'+intIndexDet+'" id="isChecked'+intIndexDet+'" value="yes" size="2" />';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = ''+myItmeCode+'<input type="hidden" name="txtcostingmethod2_'+intIndexDet+'" id="txtcostingmethod2_'+intIndexDet+'" size="40" maxlength="255" value="'+myItmeName+'"  /> <input type="hidden" name="hidHabis2'+intIndexDet+'" id="hidHabis2'+intIndexDet+'" value="'+myidHabis+'" /> <input type="hidden" name="itemcode'+intIndexDet+'" id="itemcode'+intIndexDet+'" value="'+myItmeCode+'"  size="15" style="text-align:center;" class="inplabel" readonly />';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = ''+myItmeName+'<input type="hidden" name="myParentIndex'+intIndexDet+'" name="myParentIndex'+intIndexDet+'" class="inplabel" size="4" style="text-align:right;" value="'+intIndexHeader+'" /><input type="hidden" name="myitemName'+intIndexDet+'" name="myitemName'+intIndexDet+'" class="inplabel" size="15" style="text-align:right;" value="'+myItmeName+'" /><input type="hidden" name="groupIndex'+intIndexDet+'" size="2" id="groupIndex'+intIndexDet+'" value="'+intIndexHeader+'" />';
                                
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = '<input type="hidden" name="txtDimensionID2_'+intIndexDet+'" id="txtDimensionID2_'+intIndexDet+'" value="'+myDimensionID+'"  /> <input type="text" name="txtDimensionName2_'+intIndexDet+'" name="txtDimensionName2_'+intIndexDet+'" value="'+myDimensionName+'" size="15" style="text-align:left;" class="inplabel" />';
                                
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = ''+mySOQty+'<input type="hidden" name="txtSOQty2_'+intIndexDet+'"name="txtSOQty2_'+intIndexDet+'" class="inplabel" size="15" value="'+mySOQty+'"style="text-align:right;" />';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = '<a href="javascript://" onClick="showOtherDoc2('+intIndexDet+')">'+myDeliveredQty+'</a><input type="hidden" name="txtDeliveredQty2_'+intIndexDet+'" id="txtDeliveredQty2_'+intIndexDet+'" class="inplabel" size="15" value="'+myDeliveredQty+'" />';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = ''+myRemainQty+'<input type="hidden" name="txtRemainQty2_'+intIndexDet+'" id="txtRemainQty2_'+intIndexDet+'" class="inplabel" value="'+myRemainQty+'" size="15" />';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = '<input type="text" align="right" name="txtShipQty2_'+intIndexDet+'" id="txtShipQty2_'+intIndexDet+'" value="0.00" onBlur="AkumulateQty('+intIndexDet+');checkMaxQty('+intIndexDet+');" size="15" onKeyUp="decimalin(this);" onKeyPress="return isIntOnlyNew(event);" />';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = ''+myMeasUnitName+' <input type="hidden" name="txtMeasureUnit2_'+intIndexDet+'" class="inplabel" id="txtMeasureUnit2_'+intIndexDet+'" value="'+myMeasUnit+'" size="15" />';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = '<select name="selWH2_'+intIndexDet+'" id="selWH2_'+intIndexDet+'"><option value="0">None</option><cfif qWHLocation.recordcount><cfloop query="qWHLocation"><option value="#varWHID#">#WH_Name#</option></cfloop></cfif></select>';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = '<textarea name="txtLot2_'+intIndexDet+'" id="txtLot2_'+intIndexDet+'"></textarea>';
                                    
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    <cfset dtmvalue2 = "#DateFormat(Now(),"mm/dd/yyyy")#">
                                    dtp_Identity++;
                                    objTD.innerHTML = "<INPUT id=Picker"+dtp_Identity+"_selecteddates value='#dtmvalue2#' type=hidden name='txtDueDate"+intIndexDet+"'><INPUT id=Picker"+dtp_Identity+"_visibledate type=hidden name=Picker"+dtp_Identity+"_visibledate><INPUT onblur='return ComponentArt_Calendar_PickerOnBlur(this)' onkeydown='return ComponentArt_Calendar_PickerOnKeyDown(event,this)' ondragstart='return ComponentArt_Calendar_PickerOnDragStart(this)' id=Picker"+dtp_Identity+"_picker onmouseup='return ComponentArt_Calendar_PickerOnMouseUp(this)' onselectstart='return ComponentArt_Calendar_PickerOnSelectStart(this);' class=picker onfocus='return ComponentArt_Calendar_PickerOnFocus(this)' onkeypress='return ComponentArt_Calendar_PickerOnKeyPress(event,this)' onkeyup='return ComponentArt_Calendar_PickerOnKeyUp(this)' onmousedown='return ComponentArt_Calendar_PickerOnMouseDown(this)' size=17 name=Picker"+dtp_Identity+"_picker onselect='return ComponentArt_Calendar_PickerOnSelect(this)'><DIV style='DISPLAY: none' id=Picker"+dtp_Identity+"></DIV><IMG id=calendar_button"+dtp_Identity+" onclick='ShowCalendar(\""+dtp_Identity+"\")' align=absMiddle src='#Application.stApp.Upload_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/images/date.gif'><INPUT id=Calendar"+dtp_Identity+"_selecteddates type=hidden name=Calendar"+dtp_Identity+"_selecteddates><INPUT id=Calendar"+dtp_Identity+"_visibledate type=hidden name=Calendar"+dtp_Identity+"_visibledate><DIV style='DISPLAY: none' id=Calendar"+dtp_Identity+" class=calendar></DIV><br>";
                                    objTD.innerHTML+= '<input type="Hidden" name="txtDueDateDTCIdentity'+intIndexDet+'" value="'+dtp_Identity+'">';
                                    <cfif isdate(dtmvalue2)>
                                        objSelectedDates = new Date('#dtmvalue2#');
                                    <cfelse>
                                        objSelectedDates = [];
                                    </cfif>
                                    ComponentArt_Init_Picker();
                                    ComponentArt_Init_Calendar();
            
                                    objTD = objTRDet.insertCell(objTRDet.cells.length);
                                    objTD.align = 'center';
                                    objTD.innerHTML = '<a href="javascript://" onClick="selectAddress('+intIndexDet+')">Select Address</a> <textarea name="txtRemark2_'+intIndexDet+'" id="txtRemark2_'+intIndexDet+'"></textarea>';
									
                                    document.getElementById('myTotIndex').value = intIndexDet;
                                    document.getElementById('brs').value = intIndexDet;
                                }
								document.getElementById('btnConfirm').disabled = false;
                            }	
							
							/*var objTable, objTR, objTD, intIndexHeader2;
							objTable = document.getElementById('tblNewDetailShip');
							intIndexHeader2 = objTable.rows.length;
							objTR = objTable.insertRow(intIndexHeader2);
							objTR.id = 'tr_' + intIndexHeader2;
							
							objTD = objTR.insertCell(objTR.cells.length);
							objTD.align = 'center';
							objTD.innerHTML = '<input type="Checkbox" style="display:none" onClick="CheckHeaderTable('+intIndexHeader+')" name="chkAll2_'+intIndexHeader+'" id="chkAll2_'+intIndexHeader+'" value="'+intIndexHeader+'" checked><input type="hidden" name="ParentIndex'+intIndexHeader+'" name="ParentIndex'+intIndexHeader+'" class="inplabel" size="4" style="text-align:right;" value="'+intIndexHeader+'" /><input type="hidden" name="groupIndex'+intIndexHeader+'" id="groupIndex'+intIndexHeader+'" value="'+intIndexHeader+'" /><input type="hidden" name="isHeader'+intIndexHeader+'" id="isHeader'+intIndexHeader+'" value="1" size="2" /><input style="display:none" type="checkbox" name="chk2'+intIndexHeader+'" id="chk2'+intIndexHeader+'" value="'+intIndexHeader+'" checked /><br><br>';*/
												
							countMyHeader();
                        }
                        
						function countMyHeader()
						{
							
							var totalRowNewDetail = document.getElementById('myTotIndex').value;
							var countHeader = 0;
							//alert('totalRowNewDetail = '+totalRowNewDetail)
							for(i = totalRowNewDetail; i >= 0; i--)
							{
								var hdr = document.getElementsByName('isHeader'+i).length;
								if(hdr!=0)
								{
									if(document.getElementById('isHeader'+i).value == 1)
									{
										countHeader = parseFloat(countHeader + 1);
									}
								}
							}
							document.getElementById('myindexHeader').value=countHeader; 
							//untuk menghitung ulang row setelah penambahan row baru
							var newTotRow = parseFloat(document.getElementById('myTotIndex').value);
							var myHigerValue = parseFloat(document.getElementById('myHigerValue').value);
							//alert('newTotRow = '+newTotRow+' AND myHigerValue = '+myHigerValue)
							if(newTotRow >= myHigerValue)
							{
								document.getElementById('myHigerValue').value = newTotRow;
							}
							
							var objTable, objTR, objTD, intIndex;
							objTable = document.getElementById('tblNewDetailShip');
							nowRowCount = objTable.rows.length;
							document.getElementById('Aseli').value = nowRowCount;
						}
						
                        function selectAddress(alexis)
                        {
                            var basedPath = "#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/sales/shippinginstruction/forms/";
                            arrNewPop[arrNewPop.length]=PopWindow(basedPath+"selitem_multiple.cfm?brs="+alexis+"&cboCustomer="+document.forms[0].txtAccountId.value,'Preview','500','500','scrollbars=yes,resizable=yes,location=no,status=yes');
                        }
						
                        function showOtherDoc2(alexis)
                        {
							var refnum = "#SELREFNUM#";
							var myTotIndex = document.getElementById('myTotIndex').value;
							if(myTotIndex > 0)
							{
								var itemCode = document.getElementById('itemcode'+alexis).value; 
								var txtInsNo = document.getElementById('txtInsNox').value;
								var basedPath = "#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/sales/shippinginstruction/forms/";
								arrNewPop[arrNewPop.length]=PopWindow(basedPath+"otherdoc.cfm?brs="+alexis+"&txtInsNo="+txtInsNo+"&item_code="+itemCode+"&SELREFNUM="+refnum,'Preview','700','400','scrollbars=yes,resizable=yes,location=no,status=yes');
							}
                        }
                        
                        function AkumulateQty(intIndexDet)
                        {
							
                            var myHigerValue = document.getElementById('myHigerValue').value; 
                            var itemCode = document.getElementById('itemcode'+intIndexDet).value;
							var remQty 	= eval(document.getElementById('txtRemainQty2_'+intIndexDet).value.split(",").join(""));
                            var AkumQtySI = 0;
                            for(i = myHigerValue; i >= 0; i--) 
                            {
                                if(document.getElementById('isHeader'+i) != null)
								{
									if(document.getElementById('isHeader'+i).value == 0)
									{
										var newCode 	= document.getElementById('itemcode'+i).value;
										var newShipQty 	= eval(document.getElementById('txtShipQty2_'+i).value.split(",").join(""));

										if(newCode == itemCode)
										{
											var AkumQtySI = AkumQtySI + newShipQty;
										}
										document.getElementById('txtShipQty2_'+i).value = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(newShipQty)),#Application.stApp.decimaL_range[VST_IDX]#));	
									}
								}
                            }
							if(AkumQtySI > remQty)
							{
								alert('Shipping Instruction Qty great than Remain Qty.');
								document.getElementById('txtShipQty2_'+intIndexDet).value = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(0)),#Application.stApp.decimaL_range[VST_IDX]#));
								return false;
							}
                            var myCheck = document.getElementById('totalrowdefault').value;
                            for(idx=1;idx<=myCheck;idx++)
                            {
                                var itemCodeDec = document.getElementById('chkItemHid'+idx).value;
                                if(itemCodeDec==itemCode)
                                {
                                    document.getElementById('txtShipQty_'+idx).value = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(AkumQtySI)),#Application.stApp.decimaL_range[VST_IDX]#));
                                }
                            }
                        }
						
						function DeleteItem() 
						{
							var objTable = document.getElementById('tblNewDetailShip');
							var myHigerValue = parseFloat(document.getElementById('myHigerValue').value) + 1; 
							var Aseli = parseFloat(document.getElementById('Aseli').value); 
							var objTR, objTD, intIndex;
							rowCount = objTable.rows.length;
							//alert('rowCount = '+rowCount)
							//alert('Aseli = '+Aseli)
							//alert('myHigerValue = '+myHigerValue)
							var s = parseFloat(myHigerValue) - parseFloat(rowCount)
							//alert(s)
							var totalRowNewDetail = document.getElementById('myTotIndex').value;
							for(i = myHigerValue-1; i >= 0; i--) 
							{
								if(document.getElementById('chk2'+i) != null)
								{
									//alert(document.getElementById('isChecked'+i).value)
									if(document.getElementById('chk2'+i).checked == true) 
									{
										objTable.deleteRow(i-s);
									}
								}
							}
							rowCount = objTable.rows.length;
							if(rowCount==0) document.getElementById('btnConfirm').disabled = true;
							
							// input jumlah row terakhir
							document.getElementById('myTotIndex').value = rowCount;
							document.getElementById('Aseli').value = rowCount;
							
							AkumulateQtyAfterDelete();
						}
						
						function AkumulateQtyAfterDelete(intIndexDet)
                        {
                            var myHigerValue = document.getElementById('myHigerValue').value; 
                            var AkumQtySI = 0;
							var itemCode2 = '';
							for(i = myHigerValue; i >= 0; i--) 
							{
								if((document.getElementById('chk2'+i) != null) && (document.getElementById('isHeader'+i) != null) && (document.getElementById('isHeader'+i).value == 0))
								{
									var itemCode = document.getElementById('itemcode'+i).value;
									for(i = myHigerValue; i >= 0; i--) 
									{
										if(document.getElementById('isHeader'+i) != null)
										{
											if(document.getElementById('isHeader'+i).value == 0)
											{
												var newCode 	= document.getElementById('itemcode'+i).value;
												var newShipQty 	= eval(document.getElementById('txtShipQty2_'+i).value.split(",").join(""));
		
												if(newCode == itemCode)
												{
													var AkumQtySI = AkumQtySI + newShipQty;
												}
												document.getElementById('txtShipQty2_'+i).value = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(newShipQty)),#Application.stApp.decimaL_range[VST_IDX]#));	
											}
										}
									}
									var myCheck = document.getElementById('totalrowdefault').value;
									for(idx=1;idx<=myCheck;idx++)
									{
										var itemCodeDec = document.getElementById('chkItemHid'+idx).value;
										if(itemCodeDec==itemCode)
										{
											document.getElementById('txtShipQty_'+idx).value = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(AkumQtySI)),#Application.stApp.decimaL_range[VST_IDX]#));
										}
									}
									
									var myCheck = document.getElementById('totalrowdefault').value;
									for(idx=1;idx<=myCheck;idx++)
									{
										var itemCodeDec = document.getElementById('chkItemHid'+idx).value;
										var valueCode = 0;
										for(i = myHigerValue; i >= 0; i--) 
										{
											if(document.getElementById('isHeader'+i) != null)
											{
												if(document.getElementById('isHeader'+i).value == 0)
												{
													var newCode 	= document.getElementById('itemcode'+i).value;			
													if(newCode == itemCodeDec)
													{
														valueCode = valueCode + 1;
													}
												}
											}
										}
										if(valueCode==0)
										{
											document.getElementById('txtShipQty_'+idx).value = doDecimalFormat(Round2Decimal(parseFloat(Math.abs(0)),#Application.stApp.decimaL_range[VST_IDX]#));
										}
									}
								}
							}							
						}
						
						function CheckDetailTable(thisChk,chkdetail) 
						{
							var totalRowNewDetail = document.getElementById('myTotIndex').value;
							// menentukan nilai index group row yang dicentang
							var myGroupIndex = document.getElementById('groupIndex'+chkdetail).value;
							var myHigerValue = document.getElementById('myHigerValue').value; 
							// menghitung item yang dicentang per tabel. Apabila bernilai > 0 maka header akan dicentang
							var countValue 	= 0;
							var countValuex = 0;
							//alert('myGroupIndex = '+myGroupIndex)
							// untuk menanggulangi recount row setelah delete row
							for(i = myHigerValue; i >= 0; i--) 
							{
								if((document.getElementById('groupIndex'+i) != null) && (document.getElementById('isHeader'+i) != null))
								{
									if((document.getElementById('groupIndex'+i).value == myGroupIndex) && (document.getElementById('isHeader'+i).value != 1))
									{
										countValuex = parseFloat(countValuex + 1);
									}
								}
							}
							//alert('countValuex = '+countValuex)
							for(i = myHigerValue; i >= 0; i--) 
							{
								if(document.getElementById('groupIndex'+i) != null)
								{
									if(document.getElementById('groupIndex'+i).value == myGroupIndex)
									{
										var chkDet 		= document.getElementById('chk2'+i);
										var isHeader 	= document.getElementById('isHeader'+i).value;
										if((chkDet.checked) && (isHeader != 1))
										{
											countValue = parseFloat(countValue + 1);
										}
										else
										{
											countValue = parseFloat(countValue + 0);
										}
										if(chkDet.checked) document.getElementById('isChecked'+i).value = 'yes';
										else document.getElementById('isChecked'+i).value = 'no';
									}
								}
							}
							//alert('countValuex = '+countValuex+' countValue = '+countValue)
							//alert('countValue = '+countValue+ 'dan countValuex = '+countValuex)
							if(countValue == countValuex)
							{
								document.getElementById('chkAll2_'+myGroupIndex).checked = true
								document.getElementById('chk2'+myGroupIndex).checked = true
								document.getElementById('isChecked'+myGroupIndex).value = 'yes';
							}
							else
							{
								//alert('myGroupIndex = '+myGroupIndex)
								document.getElementById('chkAll2_'+myGroupIndex).checked = false
								document.getElementById('chk2'+myGroupIndex).checked = false
								document.getElementById('isChecked'+myGroupIndex).value = 'no';
							}							
						}
						
						function CheckHeaderTable(chk)
						{
							var totalRowNewDetail = document.getElementById('myTotIndex').value;
							var myHigerValue = document.getElementById('myHigerValue').value;
							// menentukan nilai index group row yang dicentang
							var myGroupIndex = document.getElementById('groupIndex'+chk).value;
							// menghitung item yang dicentang per tabel. Apabila bernilai > 0 maka header akan dicentang
							var chkDet 		= document.getElementById('chkAll2_'+myGroupIndex);
							if(chkDet.checked)
							{
								for(i = myHigerValue; i >= 0; i--) 
								{
									if(document.getElementById('groupIndex'+i) != null)
									{
										if(document.getElementById('groupIndex'+i).value == myGroupIndex)
										{
											document.getElementById('chk2'+i).checked = true
											document.getElementById('isChecked'+i).value = 'yes';
										}
									}
								}
							}
							else
							{
								for(i = myHigerValue; i >= 0; i--) 
								{
									if(document.getElementById('groupIndex'+i) != null)
									{
										if(document.getElementById('groupIndex'+i).value == myGroupIndex)
										{
											document.getElementById('chk2'+i).checked = false
											document.getElementById('isChecked'+i).value = 'no';
										}
									}
								}
							}
						}
                    </script>
                    <input type="hidden" name="Aseli" id="Aseli" value="0">
                    <input type="hidden" name="myTotIndex" id="myTotIndex" value="0">
                    <input type="hidden" name="myHigerValue" id="myHigerValue" value="0">
                    <input type="hidden" name="myindexHeader" id="myindexHeader" value="">
                <!--- End Add by DH on March, 18 2014. Shipping Instruction Detail --->
                <tr>
                    <td>&nbsp;
                    	
                    </td>
                </tr>
               
               <!---nath. 20140317. end--->
                
                <tr id="trTabelDetail" style="display:none">
                    <td style="border:3px double black;" colspan="6">
                        <table width="100%" id="tblNewDetailShip" class="formtext" cellpadding="2" border="0" cellspacing="1">                    
                            <cfset brs = 1>
                            <input type="hidden" name="brs" value="#brs#" id="brs">
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <cfquery name="qCheckAlreadySN" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                            Select * from TAccSN_Header
                            where Approval_Status <> 4
                            And ShippingInstruction_Number = '#txtInsNo#'
                        </cfquery>
                    
                        <cfset varStatusAccess1 = REQUEST.SFSecAccess.SecStatusAccess(FILEACCESSCODE="ERSTD0862409", USERID="#evaluate("cookie.#Application.stApp.Cookie_Name[1]#")#")>
        				<input type="hidden" name="rowSplit" value="#rowSplit#">
                        <input type="hidden" name="hdnQtyRef" value="#qItem.recordcount#">
                        <cfquery name="qCekIsSN" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                            select * from TAccSN_Header
                            inner join TAccShippingInstruction_Header on TAccShippingInstruction_Header.Ref_Number = TAccSN_Header.Ref_Number
                            where  TAccShippingInstruction_Header.ShippingInstruction_Number = '#txtInsNo#'
                            and  TAccSN_Header.ShippingInstruction_Number = '#txtInsNo#'
                            And Approval_Status <> 4
                        </cfquery>
                        <cfif  varStatusAccess1 eq "Yes" and qItem.recordcount neq 0>
                            <cfif task eq "Save" or (isdefined("qGetDetail") and qGetDetail.Doc_Status lt 1)>
                                <cfset varStatusAccess = REQUEST.SFSecAccess.SecStatusAccess(FILEACCESSCODE="ERSTD0862405", USERID="#evaluate("cookie.#Application.stApp.Cookie_Name[1]#")#")>
                                <cfif  varStatusAccess eq "Yes">
                                    <input type="Button" name="btnConfirm" id="btnConfirm" value="#DO_VAR['eHRMconfirm']#" onClick="passingVars('Confirm',0);return false" style="width:100" disabled>

									<!---<input type="Button" <cfif (task neq "save" AND qGetDetail.Shipping_Status neq "ND") or qCekIsSN.recordcount>disabled</cfif> name="btnCancel" value="#DO_VAR['CancelShipping']#" onClick="passingVars('Cancel');return false" style="width:100">--->
                                <cfelse>
                                    <input type="Button" name="btnSave" value="#DO_VAR['eHRMSave']#" onClick="passingVars('Save',0);return false;" style="width:100">
                                    <cfif task eq "Save">
                                    <input type="Button" name="btnSave" value="#DO_VAR['SplitRelease']#" onClick="SplitLine();return false;" style="width:150">
                                    </cfif>
                                    <!---FUNCTION ASLINYA DI EVENT ONCLICK: passingVars('Save',1)--->
                                </cfif>
                                
                                <!--- <input type="Button" name="btnConfirm" value="#DO_VAR['ConfirmAndAddOthers']#" onClick="passingVars('Confirm',1);return false" style="width:150"> --->
                            <cfelse>
                                 <cfif task neq "save" and (qGetDetail.Doc_Status eq 3 or qGetDetail.Doc_Status eq 1) and qGetDetail.Shipping_Status eq "ND">
                                    <input type="Button" name="btnUpdate" value="#DO_VAR['Update']#" onClick="passingVars('Update',0);return false;">
                                 </cfif>
								 
								 <!---<input type="Button" <cfif (task neq "save" AND qGetDetail.Shipping_Status neq "ND") or qCekIsSN.recordcount>disabled</cfif> name="btnCancel" value="#DO_VAR['CancelShipping']#" onClick="passingVars('Cancel');return false" style="width:100">--->
                               
                            </cfif>
                                                         
                            <!--- Pindah ke menu AR|Release Shipping--->  
                                
                                                 
                        </cfif>
                        
                            <cfset vartemplate = "index.cfm">
                            <cfset varquerystring = "?FID=ERSTD08624&FUID=ERSTD0862401&menu=1">    
                            <input type="Button" name="btnBack" value="#DO_VAR['Back']#" onClick="self.location='#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/#vartemplate##varquerystring#'" style="width:100">
        					&nbsp;
                    </td>
                </tr>
                
                <cfif task eq "edit">
                    <cfquery name="qGetLogCreate" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                        Select * from THRMEmpPersonalData
                        Where User_id = #qGetDetail.Created_By#
                    </cfquery>
                    <cfif qGetDetail.Update_By neq "">
                        <cfquery name="qGetLogUpdate" datasource="#iif(isDefined('DSN'),'DSN','Attributes.DSN')#">
                            Select * from THRMEmpPersonalData
                            Where User_id = #qGetDetail.Update_By#
                        </cfquery>
                    </cfif>
                    
        			<tr>
                        <td colspan="6">
            				<table cellpadding="2" cellspacing="2" class="formtext">
            					<tr>
            						<td align="right">#DO_VAR['CreatedBy']#</td>
            						<td align="center">:</td>            
            						<td>#qGetLogCreate.First_Name# #qGetLogCreate.Middle_Name# #qGetLogCreate.Last_Name#</td>
            					</tr>
            					<tr>
            						<td align="right">#DO_VAR['creationdate']#</td>
            						<td align="center">:</td>
            						<td>#Dateformat(qGetDetail.Creation_DateTime,"mmm dd yyyy")# #Timeformat(qGetDetail.Creation_DateTime,"hh:mm tt")#</td>
            					</tr>
            					<tr>
            						<td align="right">#DO_VAR['update']# #DO_VAR['by']#</td>
            						<td align="center">:</td>
            						<td><cfif isdefined("qGetLogUpdate")>#qGetLogUpdate.First_Name# #qGetLogUpdate.Middle_Name# #qGetLogUpdate.Last_Name#</cfif></td>
            					</tr>
            					<tr>
            						<td align="right">#DO_VAR['eHRMLastUpdate']#</td>
            						<td align="center">:</td>
            						<td><cfif isdefined("qGetLogUpdate")>#Dateformat(qGetDetail.Last_Update,"mmm dd yyyy")# #Timeformat(qGetDetail.Last_Update,"hh:mm tt")#</cfif></td>
            					</tr>
            				</table>
            			</td>
                    </tr>
        		</cfif>
            </table>
        </td>
    </tr>
</table>

</form>
</body>
<script type="text/javascript">
    var tasks = "#task#";
    var arrNewPop = new Array();
	var frm = document.forms[0];
	function SplitLine() {
		frm.rowSplit.value = parseInt(frm.rowSplit.value) + 1;
		frm.selRefNum.value = frm.selRefNum.value;
		reload_page();
	}
    
    function refreshBin(row,sts,qty,code,cek)
    {
        
    	obj = document.getElementById("tdBin"+row);
    		
    	if(obj)
    	{
    		var objCostingMethod = eval("document.forms[0].txtcostingmethod_"+row);
    		if(objCostingMethod.value == 'FIFO')
    		{
    			clearBin(document.getElementById('txtShipQty_' + row), row, document.getElementById('txtcostingmethod_' + row).value);	  		
    		}
    		else
    		{			
                if(sts != 0)
                {
                	if(qty > 0)
                	{
                		var dimension_id = eval("document.forms[0].txtDimensionID_"+row).value;
                		var xx = "0";
                		
                		clearBin(document.getElementById('txtShipQty_' + row), row, document.getElementById('txtcostingmethod_' + row).value,'',code,dimension_id);
                									
                	}
                }			
    		}
    	}
    }

    function onPageLoad()
    {
    
        var thisform = 	document.frmNew;
        for(idx=1;idx<="#qItem.recordcount#";idx++)
        {
            if(document.getElementById('hdnFlagBinSourceCount_'+idx).value == 1)
        	{        	 
    		    if(document.getElementById('hidHabis' + idx).value == 1)
    			{
    			  clearBin(document.getElementById('txtShipQty_' + idx), idx, document.getElementById('txtcostingmethod_' + idx).value);
    			}
    		
        	}
        }
    }   
    
    function tickItem(thisChk,idt){
      var theChk = thisChk.form.chk;
      var len = theChk.length;
      var allChk=thisChk.form.chkAll;
      var x = 0;
      var refsub=null;
      if(theChk != null){
    	  if(len != null){
    		  //move until idt
    		  for (var j=0; j<len; j++){
    			  if(theChk[j].checked) x++;
    			  if (theChk[j].value==idt) {
    				  refsub=j;
    				  break;
    			  }
    		  }
    		  var ceksub=true;
    		  for(var i=j+1; i<len; i++){
    			  if (ceksub) {
    				  if (theChk[i].disabled)
    					  theChk[i].checked=theChk[refsub].checked;
    				  else {
    					  ceksub=false;
    				  }
    			  }
    			  if(theChk[i].checked) x++;
    		  }
    		  allChk.checked = (x >= len);
    	  }
    	  else allChk.checked = theChk.checked;
      }
    
     }
     
     function UnSelectAll(thisobj){
    	var thisform = 	document.frmNew;
    
    	if(thisform.chkAll.checked == true)thisform.chkAll.checked = false;
    }
    
    function IsSelectAll(thisobj) {
    	
    	var chkObjs = document.getElementsByName('chk');
    	if (thisobj.checked) {
    		for (i=0; i<chkObjs.length; i++) {
    			chkObjs[i].checked = true;
    			selectedDetailRows = document.getElementById('tblDO').rows.length-1;
    		}				
    	}
    	else {
    		for (i=0; i<chkObjs.length; i++) {
    			chkObjs[i].checked = false;
    			selectedDetailRows = 0
    		}
    	}
    }
    
	<!--- nath.20140317--->
	function tickItem1(thisChk,idt)
	{
		var x = 0;
		var y = document.getElementById('totalrowdefault').value;
		for (i=1; i<=y; i++) 
		{
			if(document.getElementById('chk1'+i).checked){
				x = x + 1;
			}
			else{
				x = x + 0;
			}
		}
		if(x==y)
		document.getElementById('chkAll_1').checked = true;
		else
		document.getElementById('chkAll_1').checked = false;
     }
     
     function UnSelectAll_1(thisobj){
    	var thisform = 	document.frmNew;
    
    	if(thisform.chkAll_1.checked == true)thisform.chkAll_1.checked = false;
    }
    
    function IsSelectAll_1(thisobj) {
    	var chkObjs = document.getElementById('chkAll_1');
    	var myTotDetailInduk = document.getElementById('totalrowdefault').value;
		
    	if (thisobj.checked) {
    		for (i=1; i<=myTotDetailInduk; i++) {
    			//chkObjs[i].checked = true;
				document.getElementById('chk1'+i).checked = true;
    			//selectedDetailRows = document.getElementById('tblDO1').rows.length-1;
    		}				
    	}
    	else {
    		for (i=1; i<=myTotDetailInduk; i++) {
    			//chkObjs[i].checked = false;
    			//selectedDetailRows = 0
				document.getElementById('chk1'+i).checked = false;
    		}
    	}
    }
     
	<!--- end nath.20140317--->
	 
    function refresh()
    {
        document.forms[0].selRefNum.value = "";
        frmNew.action ='';
		frmNew.method = 'post';
		frmNew.submit(); 
    }

    function reload_page() {
    	if(frmNew.selRefNum.value!="" && isLock(document.frmNew.txtInsDate) == true){
    		<cfset vartemplate = "index.cfm">
    		<cfset varquerystring = "?FID=ERSTD08624&FUID=ERSTD0862408&menu=1">	
    		frmNew.action = '#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/#varTemplate##varQueryString#&CBOTYPE=#CBOTYPE#';
    		frmNew.method = 'post';
    		frmNew.submit();
    	}
    }

    var objLookupField = ''; 
    var objTextField = ''; 
    var objPageRequestURL = '#Application.stApp.Web_Path[1]#/#Application.stApp.Home_URL[VST_IDX]#/sales/shippinginstruction/forms/cntquicksearch.cfm';
    
    function quickSearch(objSearchText, objPageRequestType, divName) {
    
        for(idx=0;idx<document.forms[0].rdoExport.length;idx++)
        {
            if(document.forms[0].rdoExport[idx].checked==true)
            {
               if(document.forms[0].rdoExport[idx].value==0)
               {
                var isExport = 0;
               }
               else
               {
                var isExport = 1;
               }
            }
        }
        
        
    	var strURL = '#Application.stApp.Web_Path[1]#/#Application.stApp.Home_URL[VST_IDX]#/include/quicksearch.cfm?PageRequestID=' + Math.random()
    			 + '&PageRequestType=' + objPageRequestType
    			 + '&SearchText=' + objSearchText.value
                 + '&CustomSearchText=' + isExport
    			 + '&PageRequestURL=' + objPageRequestURL
    			 + '&submenu=sales&CBOTYPE=#CBOTYPE#'
    			 + '&divName=' + objLookupField
    			 + '&ExtraQuery=1';
    			 
    	getAJAXContent(strURL, objLookupField, 1);
    }
    
     function objValidation() {
    	
    	if(objTextField=="selRefNum") {
    		var objNumberField = 'SONumber';
    	}
        
    	if(document.getElementById(objTextField).value == '') {
    		
    		//else{
    			alert('Please enter or choose document source!');
    		//}
    		quickSearch(document.getElementById(objTextField), objNumberField,objTextField);
    		document.getElementById(objTextField).focus();
    		return false;
    	}
    	return true;
    }


    function onEvent(){
    	// close search field
    	popLookup('no');
    	
    	if(objValidation()) {
    		
    			if(eval("document.forms[0].btnSubmit"))
    			document.frmNew.btnSubmit.disabled=true;
    			reload_page();
    			    		
    	}
    }

   

    function setObjField(TextField, LookupField) {
    	
    	if(objLookupField!="")popLookup('no');
    	objTextField = TextField;
    	objLookupField = LookupField;
    }
    
    function switched(doc,txtDoc) {
    	
    	if(doc=='RefNum'){
    		setObjField('selRefNum','divAjaxLookupRefNum'); 
    		quickSearch(txtDoc, 'SONumber','divAjaxLookupRefNum');
    	}
    }
    
     function passingVars(IsConfirm,AddNew){	
        var thisform = 	document.frmNew;
        var ItemCD = new Array();
        var ArrIDX  = new Array();
        
        var chkObjs = document.getElementsByName("chk");
        var rowSplit = thisform.rowSplit.value;
        if(IsConfirm != "Cancel")
        {
			for(a=0;a<chkObjs.length;a++) 
            {                
                if(chkObjs[a].checked == true)
                {             
                    var i = a+1;       
						
                    var SO = eval("document.frmNew.txtSOQty_"+i).value;
                    var tolerance = (parseFloat(document.frmNew.txtTolerance.value)/100) * SO;
                    var delivered = eval("document.frmNew.txtDeliveredQty_"+i).value; 
                    //var maxShipment  = parseFloat(SO) + parseFloat(tolerance) - parseFloat(delivered);
                    var maxShipment  = parseFloat(SO) - parseFloat(delivered);
                    
                    //if(parseFloat(eval("document.frmNew.txtShipQty_"+i).value) > maxShipment)
                    //{
                        //alert("Qty is greater than tolerance");
                        //return false;
                    //}    				
                    
                    var sisa = Round2Decimal((parseFloat(SO) - parseFloat(delivered)),2)
                    
                    if(parseFloat(eval("document.frmNew.txtShipQty_"+i).value) > sisa)
                    {
                        alert("Qty is greater than remaining " + sisa);
                        return false;
                    }
                    
                    if(parseFloat(eval("document.frmNew.txtShipQty_"+i).value)==0)
                  	{
    					alert("Qty must be greater than 0");
                        return false;
    				}
                }                
                
            }
        }
            
        var p = #brs#;
        var counter = 0;
       
        if (p > 1){
    		for(i=1;i<p;i++)
            {
				isChecked = false;
				if (p == 2) 
				{ 
					if (thisform.chk.checked) 
						isChecked = true;
				}
				else 
				{
					if (thisform.chk[i-1].checked) 
						isChecked = true;
				}
    			
    			if (isChecked == true)
    			{
    				counter++;
    
        				if (counter == 1 )
        				{
        					ItemCode = eval("thisform.chkItemHid"+i).value;
        					lstIdx = i;	
        				}
        				else
        				{
        					ItemCode = ItemCode + "," +eval("thisform.chkItemHid"+i).value;    					
        					lstIdx = lstIdx +"," +i;
        				}
    			}
    		}
    	}
        else
        {
            ItemCode = thisform.chkItemHid1.value;
            
            lstIdx = 1;
            counter = 1;
            thisform.chkItemHid1.checked = true;
        }
        
        if (counter == 0) 
        {    alert("Please Choose Item");
            return false;
        }                   
        else
        {        
        	if (counter == 1)
            {
                ItemCD[0] = ItemCode;
                ArrIDX[0] = lstIdx;
            }
    		else if(counter > 1)
            {
                ItemCD = ItemCode.split(",");
                ArrIDX= lstIdx.split(",");
            }
        }
		var countH = document.getElementById('myindexHeader').value;
		var countD = document.getElementById('myTotIndex').value;
		var countChk = 0;
		if(countH > 0)
		{
			for(i=0;i<=countD;i++)
			{
				if((document.getElementById('chk2'+i).checked==true) && (document.getElementById('isHeader'+i).value == 0))
				{
					if(parseFloat(document.getElementById('txtShipQty2_'+i).value)==0)
					{
						alert('Please input shipping qty.');return false;
					}
					if(document.getElementById('selWH2_'+i).value==0)
					{
						alert('Please select warehouse.');return false;
					}
					if(document.getElementById('txtRemark2_'+i).value=='')
					{
						alert('Please input destination.');return false;
					}
					countChk = countChk + 1;
				}
			}
		}
		//alert('countChk '+countChk);
		if(countChk==0)
		{
			alert('Please chek one item.');
			return false;
		}
        if(eval("document.frmNew.btnSave"))
			document.frmNew.btnSave.disabled=true;
		if(eval("document.frmNew.btnConfirm"))
			document.frmNew.btnConfirm.disabled=true;
		
        <cfset vartemplate = "index.cfm">
        <cfset varquerystring = "?FID=ERSTD08624&FUID=ERSTD0862409&menu=1">
        thisform.action = "#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/#vartemplate##varquerystring#&CBOTYPE=#CBOTYPE#&task=#task#&status="+IsConfirm+"&AddNew="+AddNew;
        thisform.submit();
     
     }
</script>

<cfinclude template="#Application.stApp.Web_Path[VST_IDX]#/#Application.stApp.Home_URL[VST_IDX]#/include/incjavascript_pickbin.cfm">
</cfoutput>
<cfsetting showdebugoutput="yes">