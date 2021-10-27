// design url
function urlConvert(params)
{
    let url = new URL(location.href);
    for(const [key,val] of Object.entries(params))
        url.searchParams.set(key,val);
    return url;
}

// load data

async function loadData(url,selector)
{
    $('#staticBackdrop').modal('show');
    await $.get(url,
        function (data) {
            $(selector).html(data);
            window.history.pushState({},'',url);
        },
    );
    $('#staticBackdrop').modal('hide');
}

// add,edit data

function addData(url,method,obj,selector,urlParameter = '')
{
    $('#staticBackdrop').modal('show');
    $.ajax({
        type: method,
        url: url + `/${urlParameter}`,
        data: obj,
        dataType: "json",
        complete: function(a)
        {
            let res = a.responseJSON;
            let type = 'danger';
            let message = "";
            if(a.status >= 200 && a.status <= 300)
            {
                type = 'success';
                message = res.message;
            }
            else if(a.status == 422)
                for(let key in res.error)
                    message += res.error[key].join(',') + "<br>";
            else
                message = res.error;
            notify(type,message);
            if(a.status >= 200 && a.status <= 300)
                loadData(url,selector);
            else
                $('#staticBackdrop').modal('hide');
        }
    });
}

// delete data

function deleteData(url,selector,urlParameter = '')
{
    $('#staticBackdrop').modal('show');
    $.ajax({
        type: "DELETE",
        url: url + `/${urlParameter}`,
        dataType: "json",
        complete: function(a)
        {
            let data = a.responseJSON;
            let type = "danger";
            let message = "Xoá thành công";
            if(a.status >= 200 && a.status <= 300)
                type = "success";
            else
                message = data.error;
            notify(type,message);
            if(a.status >= 200 && a.status <= 300)
                loadData(url,selector);
            else
                $('#staticBackdrop').modal('hide');
        }
    });
}

function loadNotifi(data)
{
    return `<li class="bell-notification">
                <a href="${data['url']}" class="media">
                    <div class="media-body"><span class="block">${data['message']}</span><span class="text-muted block-time">${dateConvert(data['created_at'])}</span></div>
                </a>
            </li>`
}

function dateConvert(date)
{
    let delta = Math.round((+new Date - new Date(date)) / 1000);
    let minute = 60,
    hour = minute * 60,
    day = hour * 24,
    week = day * 7,
    month = week * (new Date((new Date()).getYear(),(new Date()).getMonth() + 1, 0)).getDate(),
    year = month * 12;
    
    if(delta < minute)
        return delta + ' giây trước';
    else if(delta < hour)
        return Math.round(delta / minute) + ' phút trước';
    else if(delta < day)
        return Math.round(delta / hour) + ' giờ trước';
    else if(delta < week)
        return Math.round(delta / day) + ' ngày trước';
    else if(delta < month)
        return Math.round(delta / week) + ' tuần trước';
    else if(delta < year)
        return Math.round(delta / month) + ' tháng trước';
}

$(document).ready(function() {
    Echo.private(`App.Models.User.${userId}`)
    .notification(notification => {
        countNotifi++;
        $('#countNotifi').text(countNotifi);
        $('#textNotifi').text(countNotifi);
        $(loadNotifi(notification)).insertAfter('.not-head');
    });
});