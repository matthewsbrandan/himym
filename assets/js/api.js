const id = 2;
const localhost = false;
const baseURL = localhost?"http://localhost/matthewsworld.me/api/bd_himym/":"https://www.matthewsworld.me/api/bd_himym/";
const path = localhost?"../../himym/":"";
var playing = false;
var refreshTime; 
var settings = {
    "async": true,
    "crossDomain": true,
    "url": baseURL+"episodes?id="+id,
    "method": "GET",
    "headers": {
        "content-type": "application/json", "charset": "utf-8"
    }
};

// ONLOAD
$(function(){ $('#nav-bar a.active').click(); });

async function progress(){
    videoCurrentToggle(false);
    settings.url = baseURL+'currentEpisode?id='+id;
    $.ajax(settings).done(function (data) {
        if(data.data.hasOwnProperty('finished')){
            $('.currentProgress').html('100%');
            $('#progressBar').css('width','100%');
            $('#powerRange').val('208');
        }else{
            let percent = Math.round((data.data.id_ep * 100)/208);
            $('.currentProgress').html(percent+'%');
            $('#progressBar').css('width',percent+'%');
            $('#powerRange').val(data.data.id_ep);
        }
        rangeLabel();
    });

    $('.container').hide('slow');
    $('#currentContainer').show('slow');
}
async function progressTo(){
    settings.url = baseURL+'progressTo?id='+id+'&target='+$('#powerRange').val();
    
    $.ajax(settings).done(function (data) {
        let percent = Math.round(($('#powerRange').val() * 100)/208);
        $('.currentProgress').html(percent+'%');
        $('#progressBar').css('width',percent+'%');
    });

    $('.container').hide('slow');
    $('#currentContainer').show('slow');
}
async function resetProgress(){
    settings.url = baseURL+'resetProgress?id='+id;
    
    $.ajax(settings).done(function (data) {
        $('.currentProgress').html('0%');
        $('#progressBar').css('width','1%');
        $('#powerRange').val('0');
        rangeLabel();
    });

    $('.container').hide('slow');
    $('#currentContainer').show('slow');
}
async function currentEpisode(){
    if(!playing){
        playing = true;
        settings.url = baseURL+'currentEpisode?id='+id;
        
        $.ajax(settings).done(function (data) {
            if(data.data.hasOwnProperty('finished')){
                alert('Finished');
            }else{
                let url = path+'video/s'+data.data.season+'/ep'+data.data.ep+'.mp4'
                $('#playContainer video').attr('src',url);
                $('#playContainer video')[0].currentTime=data.data.currentTime;
                refreshCurrentTime(data.data.id_ep);
                $('#playContainer video').trigger('play');
                videoCurrentFormat({
                    "nome": data.data.nome,
                    "season": data.data.season,
                    "episode": data.data.ep,
                    "favorite": data.data.favorite
                });
                videoCurrentToggle(true);
            }
        });
    }

    $('.container').hide('slow');
    $('#playContainer').show('slow');
}
async function randomEpisode(){
    playing = false;
    let ep_id = getRandomInt(209,1);

    settings.url = baseURL+'episodeFromId?id='+id+'&ep_id='+ep_id;
    
    $.ajax(settings).done(function (data) {
        let url = path+'video/s'+data.data.season+'/ep'+data.data.ep+'.mp4'
        $('#playContainer video').attr('src',url);
        $('#playContainer video')[0].currentTime=data.data.currentTime;
        refreshCurrentTime(data.data.id_ep);
        $('#playContainer video').trigger('play');
        videoCurrentFormat({
            "nome": data.data.nome,
            "season": data.data.season,
            "episode": data.data.ep,
            "favorite": data.data.favorite
        },true);
        videoCurrentToggle(true);
    });

    $('.container').hide('slow');
    $('#playContainer').show('slow');
}
async function episodes(season = 1){
    videoCurrentToggle(false);
    settings.url = baseURL+'episodes?id='+id+'&season='+season;
    
    $.ajax(settings).done(function (data) {
        let div, h2, span;
        $('#episodesContainer .cardContainer').html('');
        data.data.forEach(function(response){
            div  = $('<div />').addClass('card').on('click',()=> playEpisode(response));
            h2 = $('<h2 />').html(response.nome).css('text-align', 'center');
            span = $('<span />').html("Season "+response.season+" | Episode "+response.ep);
            div.append(h2);
            div.append(span);
            $('#episodesContainer .cardContainer').append(div);
        });
    });

    $('.container').hide('slow');
    $('#episodesContainer').show('slow');
}
async function favorites(){
    videoCurrentToggle(false);
    settings.url = baseURL+'favorites?id='+id;
    
    $.ajax(settings).done(function (data) {
        let div, h2, span;
        $('#favoritesContainer .cardContainer').html('');
        data.data.forEach(function(response){
            div  = $('<div />').addClass('card').on('click',()=> playEpisode(response));
            h2 = $('<h2 />').html(response.nome).css('text-align', 'center');
            span = $('<span />').html("Season "+response.season+" | Episode "+response.ep);
            div.append(h2);
            div.append(span);
            $('#favoritesContainer .cardContainer').append(div);
        });
    });

    $('.container').hide('slow');
    $('#favoritesContainer').show('slow');
}
async function toggleFavorite(video){
    settings.url = baseURL+'toggleFavorite?id='+id+'&season='+video.season+'&episode='+video.episode;
    
    $.ajax(settings).done(function (data) {
        if(data.response){
            if($('#videoCurrent #videoCurrentFavorite i').hasClass('bx-heart')){
                $('#videoCurrent #videoCurrentFavorite i').removeClass('bx-heart').addClass('bxs-heart');
            }
            else{
                $('#videoCurrent #videoCurrentFavorite i').removeClass('bxs-heart').addClass('bx-heart');
            }
            
        }
    });
}
async function nextEpisode(video){
    settings.url = baseURL+'nextEpisode?id='+id+'&season='+video.season+'&episode='+video.episode;
    
    $.ajax(settings).done(function (data) {
        let url = path+'video/s'+data.data[0].season+'/ep'+data.data[0].ep+'.mp4'
        $('#playContainer video').attr('src',url);
        $('#playContainer video')[0].currentTime=data.data[0].currentTime;
        refreshCurrentTime(data.data[0].id_ep);
        $('#playContainer video').trigger('play');
        videoCurrentFormat({
            "nome": data.data[0].nome,
            "season": data.data[0].season,
            "episode": data.data[0].ep,
            "favorite": false
        });
        videoCurrentToggle(true);
    });
}
function playEpisode(video){
    let url = path+'video/s'+video.season+'/ep'+video.ep+'.mp4'
    $('#playContainer video').attr('src',url);
    $('#playContainer video')[0].currentTime=video.currentTime;
    refreshCurrentTime(video.id_ep);
    $('#playContainer video').trigger('play');
    videoCurrentFormat({
        "nome": video.nome,
        "season": video.season,
        "episode": video.ep,
        "favorite": false
    });
    videoCurrentToggle(true);
    $('.nav__link').removeClass('active');
    $('.nav__play').addClass('active');
    $('.container').hide('slow');
    $('#playContainer').show('slow');
}
// FUNCTION TOGGLE videoCurrent
function videoCurrentFormat(video, random = false){
    // RESET
    $('#videoCurrent #videoCurrentFavorite').prop('onclick',null).off('click');
    $('#videoCurrent #videoCurrentNext').prop('onclick',null).off('click');
    // CONFIG
    if(video.favorite=="1"){
        $('#videoCurrent #videoCurrentFavorite i').removeClass('bx-heart').addClass('bxs-heart');
    }else{
        $('#videoCurrent #videoCurrentFavorite i').removeClass('bxs-heart').addClass('bx-heart');
    }
    $('#videoCurrent #videoCurrentFavorite').on('click',()=> toggleFavorite(video));
    $('#videoCurrent h4').html(video.season+'.'+video.episode+" | "+video.nome);
    $(document).prop('title',video.season+'.'+video.episode+" | "+video.nome);
    if(random){
        $('#videoCurrent #videoCurrentNext').on('click',()=> randomEpisode());
    }else{
        $('#videoCurrent #videoCurrentNext').on('click',()=> nextEpisode(video));
    }
}
function videoCurrentToggle(visible){
    if(visible){
        $('.videoCurrent').css('display','flex');
        setTimeout(function(){ $('#header').addClass('header_opacity'); }, 3000);  
    }else{
        $('.videoCurrent').hide('slow');
        $('#header').removeClass('header_opacity');
        $(document).prop('title','HIMYM');
    }
}
// FUNCTION RANDOM
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min;
}
async function refreshCurrentTime(id_ep){
    refreshTime = setInterval(function(){
        if(!$('#playContainer video')[0].paused){
            settings.url = baseURL+'currentTime?id='+id+'&id_ep='+id_ep+'&currentTime='+$('#playContainer video')[0].currentTime;
            $.ajax(settings).done();
        }
    }, 5000);
}

var arrRange = new Array('Nenhum Episódio Assistido...');
for(s=1;s<=2;s++){ for(ep=1;ep<=22;ep++){ arrRange.push('Season '+s+' Ep. '+ep); } }
for(ep=1;ep<=20;ep++){ arrRange.push('Season 3 Ep. '+ep); }
for(s=4;s<=9;s++){ for(ep=1;ep<=24;ep++){ arrRange.push('Season '+s+' Ep. '+ep); } }
function rangeLabel(){ $("label[for='powerRange']").html(arrRange[$('#powerRange').val()]); }