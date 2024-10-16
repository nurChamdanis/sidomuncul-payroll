let areaTable;
let areaGrupTable;
let companyId = $('#company_id');
let cpmsToken = $('#cpms_token');
let listArea = $('#list_area');
let listAreaGrup = $('#list_area_grup');
let listPayrolRules = $('#list_payroll_rules');
let segment = $('#segment');

const withScroll = {
    retrieve: true,
    paging:false,
    scrollCollapse: true,
    scrollY: '248px'
};

const withLanguage = {
    language: {
        'processing': lang.Shared.datatable_processing,
        'emptyTable': lang.Shared.datatable_emptyTable,
        'info': lang.Shared.datatable_info,
        'infoEmpty': lang.Shared.datatable_infoEmpty,
        'paginate': {
            first: lang.Shared.datatable_paginate_first,
            last: lang.Shared.datatable_paginate_last,
            next: lang.Shared.datatable_paginate_next,
            previous: lang.Shared.datatable_paginate_previous
        },
        'search': lang.Shared.datatable_search,
        'lengthMenu': lang.Shared.datatable_lengthMenu,
    },
}

$(function(){
    loadAllowanceArea();
    loadAllowanceAreaGrup();
    loadAllowanceRules();
});

companyId.on('change',function(){
    loadAllowanceArea();
    loadAllowanceAreaGrup();
    loadAllowanceRules();
});

/**
 * Load Area
 */
async function loadAllowanceArea()
{
    const checkedValue = (listArea.val()).split(',');
    const companyid = companyId.val() ?? '';
    const response = await fetch(`${SITE_URL}master_potongan/area?company_id=${companyid}`);
    const result = await response.json();
    const enabled = segment.val() == 'id' ?  'disabled' : '';
    const html = result.data.map(function(item){
        let checked = checkedValue.includes(item.work_unit_id) ? 'checked' : '';
        return `
            <tr>
                <td>${item.name}</td>
                <td class="text-center">
                    <div class="checkbox checkbox-custom">
                        <input ${enabled} ${checked} class="areaCheckbox" id="area_${item.work_unit_id}" type="checkbox" name="area[]" value="${item.work_unit_id}">
                        <label for="area_${item.work_unit_id}"></label>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    $('#area_body').html(html);
    
    areaTable = $('.areaTable').DataTable({...withScroll, ...withLanguage});
}
/**
 * Load Area
 */

/**
 * Load Area
 */
async function loadAllowanceAreaGrup()
{
    const checkedValue = (listAreaGrup.val()).split(',');
    const companyid = companyId.val() ?? '';
    const response = await fetch(`${SITE_URL}master_potongan/area_grup?company_id=${companyid}`);
    const result = await response.json();
    const enabled = segment.val() == 'id' ?  'disabled' : '';
    const html = result.data.map(function(item){
        let checked = checkedValue.includes(item.system_code) ? 'checked' : '';
        return `
            <tr>
                <td>${item.system_value_txt}</td>
                <td class="text-center">
                    <div class="checkbox checkbox-custom">
                        <input ${enabled} ${checked} class="areagrupCheckbox" id="area_grup_${item.system_code}" type="checkbox" name="areagrup[]" value="${item.system_code}">
                        <label for="area_grup_${item.system_code}"></label>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    $('#areagroup_body').html(html);
    
    areaGrupTable = $('.areaGrupTable').DataTable({...withScroll, ...withLanguage});
}
/**
 * Load Area
 */

/**
 * Load Rules
 */
async function loadAllowanceRules()
{
    const checkedValue = (listPayrolRules.val()).split(',');
    const companyid = companyId.val() ?? '';
    const response = await fetch(`${SITE_URL}master_potongan/payroll_rules?company_id=${companyid}`);
    const result = await response.json();
    const enabled = segment.val() == 'id' ?  'disabled' : '';
    const html = result.data.map(function(item){
        let checked = checkedValue.includes(item.payroll_rules_id) ? 'checked' : '';
        return `
            <li>
                <div class="checkbox checkbox-custom">
                    <input ${enabled} ${checked} class="payrollrulesCheckbox" id="payrollrules_${item.payroll_rules_id}" type="checkbox" name="payrollrules[]" value="${item.payroll_rules_id}">
                    <label for="payrollrules_${item.payroll_rules_id}">${item.rules_name}</label>
                </div>
            </li>
        `;
    }).join('');

    $('#deduction_rules').html(html);
}
/**
 * Load Rules
 */
