
// to start on page load
$(document).ready(function(){

    $( window ).load(function() {
        // Run code
        if($('#hidden').val()){
            $('#show').val($('#hidden').val());
            $('#form').submit();
        }
      });

    // function to give options for shows
    $("#show").autocomplete({
        // start right away at typing
        minLength: 1,
        source: function(request, response){
            var query = request.term;
            // send ajax request to get information 
            $.ajax({
                dataType: "json",
                type : 'Get',
                url: 'controller.php?shows='+query,
                success: function(data) {
                    response($.map( data, function(item) {
                        // add just names to html
                        $("#choices").html('');
                        var array = [];
                        for(var x = 0; x < item.length; x++){
                            array.push( {
                                label: item[x].seriesName,
                                value: item[x].seriesName
                            });
                        }
                        return array;
                    }));
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    });
    // submission of form to find a show
    $('#form').submit(function(event) {
        // changes the url
        history.pushState(null, '', $("#show").val());
        // starts the request for the show data
        $.ajax({
            type:"GET",
            url:"controller.php?show="+$("#show").val(),
            error: function() {
            },
            success:function (data) {
                // clears the body for the next show
                $("ul").html('');
                $("#left").html('');
                $("#right").html('');
                $("#episode1").html('');
                $("#episode2").html('');
                $("#episode3").html('');
                $("#title").html('');
                $("#footer").html('');
                // makes JSON readable as js
                var string = JSON.parse(data);
                console.log(string);
                // data to append from shows
                var title = "<h1>" + string.seriesName + "</h1>";
                var image = "<img id='mainPicture' src='https://www.thetvdb.com/banners/" + string.banner + "' alt='banner'>";
                var network = "<h3 id='network'>" + string.network + "</h3>";
                var description = "<p id='mainOverview'>" + string.overview + "</p>";
                var imdb = "<a id='imdb' href='https://www.imdb.com/title/" + string.imdbId + "/?ref_=inth_ov_tt'>IMDB" + "</a>";
                // appending data
                $("#title").append(title);
                $("#left").append(image);
                $("#left").append(network);
                $("#right").append(description);

                var episodeName = "<h2>" + string.episode1Name + "</h2>";
                var episodePicture = "<img class='pictures' src='https://www.thetvdb.com/banners/" + string.episode1fileName + "' alt='banner'>";
                var episodeDescription = "<p>" + string.episode1overview + "</p>";
                $("#episode1").append(episodeName);
                $("#episode1").append(episodePicture);
                $("#episode1").append(episodeDescription);

                var episodeName = "<h2>" + string.episode2Name + "</h2>";
                var episodePicture = "<img class='pictures' src='https://www.thetvdb.com/banners/" + string.episode2fileName + "' alt='banner'>";
                var episodeDescription = "<p>" + string.episode2overview + "</p>";
                $("#episode2").append(episodeName);
                $("#episode2").append(episodePicture);
                $("#episode2").append(episodeDescription);

                var episodeName = "<h2>" + string.episode3Name + "</h2>";
                var episodePicture = "<img class='pictures' src='https://www.thetvdb.com/banners/" + string.episode3fileName + "' alt='banner'>";
                var episodeDescription = "<p>" + string.episode3overview + "</p>";
                $("#episode3").append(episodeName);
                $("#episode3").append(episodePicture);
                $("#episode3").append(episodeDescription);


                $("#footer").append(imdb);
                // reset the form to be blank
                $('#show').val('');
            }
        });
        // stop form submission from changing pages
        event.preventDefault();
    });

});