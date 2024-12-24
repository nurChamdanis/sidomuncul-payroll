$(document).ready(function () {

    // Input Text alphabet + number
    $('.both').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
    });

    // Input Text alphabet
    $('.alphabet').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z ]/g, '');
    });

    // Input Text alphabet
    $('.alphabet-nospace').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z]/g, '');
    });

    // Input Text number
    $('.number').on('input', function() {
        this.value = this.value.replace(/[^0-9 ]/g, '');
    });

    // Input Text number
    $('.phonenumber').on('input', function() {
        this.value = this.value.replace(/[^0-9-+]/g, '');
    });

    // Input Text number
    $('.email').on('input', function() {
        this.value = this.value.replace(/[^0-9@.]/g, '');
    });

    // Input Text number
    $('.address').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9. ]/g, '');
    });

    // Input Text number
    $('.npwp').on('input', function() {
        this.value = this.value.replace(/[^A-Z0-9-.]/g, '');
    });

    // Input Text number
    $('.website').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9.]/g, '');
    });

    // Input Text url
    $('.url').on('input', function() {
        this.value = this.value.replace(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/g, '');
    });
	
	//setting default datatable
    $.extend(true, $.fn.dataTable.defaults, {
        ordering: false,
        paging: true,
        lengthMenu: [[10,25, 50, 100, 200, -1], [10,25, 50, 100, 200, 'All']],
        //dom : '<"top">rt<"bottom"lpi><"clear">',
        pagingType: 'full_numbers',
        language: {
            "processing": "Memuat data. Silahkan tunggu...",
            "emptyTable": "Belum ada data.",
            'info': "Menampilkan _START_ - _END_ dari _TOTAL_ baris",
            'infoEmpty': "Menampilkan 0 sampai 0 dari 0  baris",
            'paginate': {
                first: "<i class='fa  fa-angle-double-left'></i>",
                last: "<i class='fa  fa-angle-double-right'></i>",
                next: "<i class='fa  fa-angle-right'></i>",
                previous: "<i class='fa  fa-angle-left'></i>"
            },
            'search': 'Cari',
            'lengthMenu': "Per _MENU_  Baris",
        }
    });
	
    //setting default datatable
    //added by ark.misbah 20170324
    $.fn.select2.defaults.defaults = $.extend($.fn.select2.defaults.defaults, {
            allowClear: false 
            , closeOnSelect: true
            , placeholder: 'Pilih...'
            // , minimumResultsForSearch: 15
            , language: {
                    //errorLoading: function(){ return ""; }
                    inputTooLong: function(e){ var t=e.input.length-e.maximum; return "Harap hapus "+t+" karakter lagi"; }
                    , inputTooShort: function(e){ var t=e.minimum-e.input.length; return "Harap masukkan "+t+" karakter lagi"; }
                    , loadingMore: function(){ return "Mengambil data"; }
                    , maximumSelected: function(e){ return "Anda hanya dapat memilih "+e.maximum+" pilihan"; }
                    , noResults: function(){ return "Tidak ada hasil ditemukan"; }
                    , searching: function(){ return "Mencari..."; }
            },
    });
    
    
    //setting default untuk datepicker
    //added by ark.misbah 20170329
    $.fn.datepicker.dates['id'] = {
        days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
        daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        daysMin: ["Mg", "Sn", "Sl", "Rb", "Km", "Jm", "Sb"],
        months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
        monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
        today: "Today",
        clear: "Clear",
        format: "dd/mm/yyyy",
        titleFormat: "MM yyyy",
        weekStart: 0
    };
    
    $.fn.datepicker.defaults = $.extend($.fn.datepicker.defaults, {
        autoclose : true,
        todayHighlight : true,
        format : 'dd/mm/yyyy',
        language : 'id'
    });
    
    //setting default untuk moment.js bahasa indonesia
    //daterangepicker.js menggunakan moment.js
    //added by ark.misbah 20170329
    moment.locale('id', {
        months : 'Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_November_Desember'.split('_'),
        monthsShort : 'Jan_Feb_Mar_Apr_Mei_Jun_Jul_Ags_Sep_Okt_Nov_Des'.split('_'),
        weekdays : 'Minggu_Senin_Selasa_Rabu_Kamis_Jumat_Sabtu'.split('_'),
        weekdaysShort : 'Min_Sen_Sel_Rab_Kam_Jum_Sab'.split('_'),
        weekdaysMin : 'Mg_Sn_Sl_Rb_Km_Jm_Sb'.split('_'),
        longDateFormat : {
            LT : 'HH.mm',
            LTS : 'HH.mm.ss',
            L : 'DD/MM/YYYY',
            LL : 'D MMMM YYYY',
            LLL : 'D MMMM YYYY [pukul] HH.mm',
            LLLL : 'dddd, D MMMM YYYY [pukul] HH.mm'
        },
        meridiemParse: /pagi|siang|sore|malam/,
        meridiemHour : function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'pagi') {
                return hour;
            } else if (meridiem === 'siang') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'sore' || meridiem === 'malam') {
                return hour + 12;
            }
        },
        meridiem : function (hours, minutes, isLower) {
            if (hours < 11) {
                return 'pagi';
            } else if (hours < 15) {
                return 'siang';
            } else if (hours < 19) {
                return 'sore';
            } else {
                return 'malam';
            }
        },
        calendar : {
            sameDay : '[Hari ini pukul] LT',
            nextDay : '[Besok pukul] LT',
            nextWeek : 'dddd [pukul] LT',
            lastDay : '[Kemarin pukul] LT',
            lastWeek : 'dddd [lalu pukul] LT',
            sameElse : 'L'
        },
        relativeTime : {
            future : 'dalam %s',
            past : '%s yang lalu',
            s : 'beberapa detik',
            m : 'semenit',
            mm : '%d menit',
            h : 'sejam',
            hh : '%d jam',
            d : 'sehari',
            dd : '%d hari',
            M : 'sebulan',
            MM : '%d bulan',
            y : 'setahun',
            yy : '%d tahun'
        },
        week : {
            dow : 1, // Monday is the first day of the week.
            doy : 7  // The week that contains Jan 1st is the first week of the year.
        }
    });
    moment.locale('id');
    
});

function workingDaysBetweenDates(startDate, endDate) {
  
    // Validate input
    if (endDate < startDate)
        return 0;
    
    // Calculate days between dates
    var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
    startDate.setHours(0,0,0,1);  // Start just after midnight
    endDate.setHours(23,59,59,999);  // End just before midnight
    var diff = endDate - startDate;  // Milliseconds between datetime objects    
    var days = Math.ceil(diff / millisecondsPerDay);
    
    // Subtract two weekend days for every week in between
    var weeks = Math.floor(days / 7);
    days = days - (weeks * 2);

    // Handle special cases
    var startDay = startDate.getDay();
    var endDay = endDate.getDay();
    
    // Remove weekend not previously removed.   
    if (startDay - endDay > 1)         
        days = days - 2;      
    
    // Remove start day if span starts on Sunday but ends before Saturday
    if (startDay == 0 && endDay != 6)
        days = days - 1  
            
    // Remove end day if span ends on Saturday but starts after Sunday
    if (endDay == 6 && startDay != 0)
        days = days - 1  
    
    return days;
}




























