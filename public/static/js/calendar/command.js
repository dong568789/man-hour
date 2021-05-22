var selectDate = [];
var applyDate = [];
var showDayFlag = true;
(function ($) {
//切换年份（所选年份变蓝出现下划线）
    $('.items-scroll').on('click', '.item', function () {
        $(this).addClass('type').siblings().removeClass('type')
        $(this).find('.normal').addClass('wink')
        $($(this).siblings()).find('.normal').removeClass('wink')
        // _flag1 = 1;
        // currPageNum1 = 1;
    })
    console.log(allDate, readonly);

    if (allDate.length > 0) {
        getYear2();
    } else {
       getYear();
    }

    function getYear2() {
        var a,b,c;
        $.each(allDate, function (k, date) {
            a = new Date(date);
            b = a.getFullYear();
            c = a.getMonth();

            console.log(b, c);
            var rc = c + 1;

            setHtml(b, rc,0)
        });

        getCalendar('', b, c);
        $($(".items-scroll").children("div").last()).addClass('type');
        $($(".items-scroll").children("div").children("div").last()).addClass('wink');

    }
//获取年份
    /*
    * a ： 获取今天的年份及日期
    * b ： 获取当前年份
    * c ： 获取当前月份
    * rc : 获取的月份（因为显示的是减一的 但算的是用正常的算的 所以这里设置2个变量）
    * */

    function getYear(date) {
        var a = new Date();
        var b = a.getFullYear();
        var c = a.getMonth();

        console.log(b, c);
        var rc = c + 1;
        //$('.items-scroll').empty();
        //一次展示往前月的日历
        for (var i = 1; i >= 0; i--) {
            setHtml(b, rc, i)
        }
        getCalendar('', b, c);
        $($(".items-scroll").children("div").last()).addClass('type');
        $($(".items-scroll").children("div").children("div").last()).addClass('wink');
    }

    function setHtml(b,rc,i) {
        var html1 = ``;
        //如果5月后的月数大于12
        if (rc - i >= 1) {
            //（主要用于保持月份格式是2位数）
            var rc3 = rc - i
            if(rc - i < 10) {
                html1 = `<div class="item" data-year="${b}" data-month="${rc3}" onclick="getCalendar(this,${b},${rc3-1})">${b}.${'0'+rc3}<div class="normal "></div>
        </div>`
            } else {
                html1 = `<div class="item" data-year="${b}" data-month="${rc3}" onclick="getCalendar(this,${b},${rc3-1})">${b}.${rc3}<div class="normal "></div>
        </div>`
            }
        } else {

            console.log("b", b-1);
            var rc2 = rc - i + 12;
            html1 = `<div class="item" data-year="${b-1}" data-month="${rc2}" onclick="getCalendar(this,${b-1},${rc2-1})">${b-1}.${rc2}<div class="normal "></div>
      </div>`
        }
        $('.items-scroll').append(html1)
    }
//生成日历

})(jQuery);

function getCalendar(obj, b, c) {
    jQuery('.d_content1').empty();
    var date = b + "-" + (c + 1);
    console.log("date", date);
    var T_today = new Date();
    var T_year = T_today.getFullYear();
    var T_month = T_today.getMonth() + 1;
    var T_date = T_today.getDate();

    _year = new Date(date).getFullYear();
    _month = new Date(date).getMonth() + 1;

    if (_month < 10) {
        _month = '0' + _month;
    }

    jQuery('.d_content2').empty();
    var listMonth = new Array(31, 28 + lYear(b), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    //计算处理月第一天是周几
    var mD1 = new Date(b, c, 1);
    //md1 ：星期几   mD1：日期格式
    var md1 = mD1.getDay();

    //计算日历行数
    tr_str = Math.ceil((md1 + listMonth[c]) / 7);
    //动态渲染日历盒子
    for (var i = 0; i < tr_str; i++) {                    //日历有多少行
        var html2 = `<div class=date_Dates>`               //每行插入的html代码
        for (var k = 0; k < 7; k++) {                     //每行7天
            inx = i * 7 + k;
            data_str = inx - md1 + 1;                         //日期数字

            var new_data_str = data_str;
            if (data_str < 10) {
                new_data_str = '0' + data_str;
            }
            if (data_str <= 0 || data_str > listMonth[c]) {
                // 空的星期
                html2 = html2 + `<div class="date_Date2"><div class=course_state></div></div>`
            } else if (_year == T_year && _month == T_month && T_date == data_str) {

                //今天
                html2 = html2 + `<div class="date_Date4" id="${_year}-${_month}-${new_data_str}" onclick=selectDay(this,"${_year}","${_month}","${new_data_str}")>${data_str}<span class="icon">今日</span></div>`
            } else {
                let _tDate = _year + '-' + _month + '-' + data_str;

                html2 = html2 + `<div class="date_Date4" id="${_year}-${_month}-${new_data_str}" onclick=selectDay(this,"${_year}","${_month}","${new_data_str}")>${data_str}<span class="icon"></span></div>`

            }
            // 正常星期内容
        }
        html2 = html2 + `</div>`
        //在循环体中，每一行有7个盒子，所以在一行内生成7次dom元素，再一起添加到父盒子中
        jQuery('.d_content1').append(html2);
    }

    defaultDate();
    checkedStyle();
}
//判断闰年
function lYear(year) {
    if (year % 4 === 0 && year % 100 !== 0 || year % 100 === 0 && year % 400 === 0) { //能被4整除且不能被100整除 ，能被100整除且能被400整除
        return 1
    } else {
        return 0
    }
}

function selectDay(obj, year, month, day)
{
    var date = year + "-" + month + "-" + day;
    if (jQuery(obj).hasClass("disabled") || readonly){
        return
    }
    if(jQuery(obj).hasClass('act')){
        jQuery(obj).removeClass('act');
        selectDate.remove(date);
    }else{
        jQuery(obj).addClass('act');
        selectDate.push(date);
    }
    console.log(selectDate);
}

function checkedStyle()
{
    jQuery.each(selectDate, function(i, v){
        jQuery('#' + v).addClass("act");
    });
}

function ajaxData(url) {
    LP.http.jQueryAjax.post(url).then(function (res) {
        if (res.result == 'api'){
            applyDate = res.data
            defaultDate()
        }
    }, function (err) {
        LP.tip.toast_interface({content: err.$json.message});
    });
}

function defaultDate ()
{
    jQuery.each(applyDate, function (key, items) {

        console.log(key);
        var flag = "";
        if (key == "pass") {
            flag = "已通过";
        } else if(key == "applying") {
            flag = "审核中";
        } else if(key == "reject") {
            flag = "驳回";
        }
        if (items.dates.length > 0) {

            jQuery.each(items.dates, function (k, v) {
                console.log(k,v);
                jQuery('#' + v).find('.icon').html(flag)
                jQuery('#' + v).addClass("disabled");
            })
        }
    })
}

Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
}
