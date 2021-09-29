$(document).ready(function(){
    //Filter
    $('.filter .item > .controls').on('click', '.checkbox-group', function(){
        if( $(this).attr('data-status') =='inactive' ){
            $(this).find('input').prop('checked', true);
            $(this).attr('data-status','active'); }
        else{
            $(this).find('input').prop('checked', false);
            $(this).attr('data-status','inactive'); }
        let data = {};
        $('input[type="checkbox"]:checked').each((i,val) => {
            if(data.hasOwnProperty(val.name))
                data[val.name].push(val.value);
            else
                data[val.name] = [val.value];
        });
        let url = new URL(location.href);
        url.searchParams.delete('ct');
        url.searchParams.delete('ctry');
        url.searchParams.delete('sp');
        url.searchParams.delete('page');
        for(let i in data)
            url.searchParams.set(i,data[i].join(' '));
        location.href = url;
    });
    $('#sort li').click(function(){
        let url = new URL(location.href);
        url.searchParams.delete('page');
        url.searchParams.set('sort',$(this).attr('data-id'));
        location.href = url
    });
});