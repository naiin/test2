/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {

    //to get the adding of the answers to the questions
    var currentATQ = '#answersToQuestion';
    currentATQ = String(currentATQ) + '1';
    var i = $('#currentATQ p').size() + 2;
    var numSlides = 1;
    var numAnswersForQuestion = 2;
    var currentSlide = 1;
    var switchToSlideNum = 1;
    var slidesArray = [1];
    var readonly = false;


    $(document).on('click', '#addAnswer', function()
    {
        addAnswer('', false, 'normal');
        return false;
    });

    $(document).on("click", "#addAnswerWithCorrect", function() {
        addAnswer('', false, 'withCorrectAns');
        return false;
    });

    $(document).on("click", "#addAnswerWithOdds", function() {
        addAnswer('', false, 'withProbability');
        return false;
    });

    function addAnswer(answer, readonly, type) {
        var numAnswersForQuestion = $("#answersToQuestion" + String(currentSlide) + " > p").length;
        if (numAnswersForQuestion < 4) {
            if (readonly === false && type === 'normal') {
                $('<p><label for="answers"><input type="text" value="' + answer + '" id="answer" size="20" name="answer[' + String(currentSlide - 1) + '][' + String(numAnswersForQuestion) + ']" placeholder="Answer to question" required/></label> <button id="remove" type="button" class="btn">Remove</button></p>').appendTo($(currentATQ));
            }
            else if (type === 'withCorrectAns') {
                $('<p><label for="answers"><input type="text" value="' + answer + '" id="answer" size="20" name="answer[' + String(currentSlide - 1) + '][' + String(i) + ']" placeholder="Answer to question" required/></label></p>'
                        + '<div class="answer-radio"><input type="radio" name="rightOrWrong[' + String(currentSlide - 1) + ']" id="rightOrWrong[' + String(currentSlide - 1) + '][' + String(i) + ']" class="make-inline-radio" value="' + String(i) + '" /><label for="rightOrWrong[' + String(currentSlide - 1) + '][' + String(i) + ']"> Incorrect</label></div>').appendTo($(currentATQ));
            }
            else if (type === 'withProbability') {
                $('<p><label for="answers"><input type="text" value="' + answer + '" id="answer" size="20" name="answer[' + String(currentSlide - 1) + '][' + String(i) + ']" placeholder="Answer to question" required/></label><label for="probability[' + String(currentSlide - 1) + '][' + String(i) + ']"><input type="text" id="answer" size="10" name="probability[' + String(currentSlide - 1) + '][' + String(i) + ']" value="" placeholder="Probablity %" required=""></label> <button id="remove" type="button" class="btn">Remove</button></p>').appendTo($(currentATQ));
            }
            else {
                $('<p><label for="answers"><input type="text" value="' + answer + '" id="answer" size="20" name="answer[' + String(currentSlide - 1) + '][' + String(i) + ']" placeholder="Answer to question" readonly></label></p>').appendTo($(currentATQ));
            }
            i++;
        }
    }

    $(document).on('click', '#remove', function()
    {
        editerPrep();
        var numAnswersForQuestion = $("#answersToQuestion" + String(currentSlide) + " > p").length;
        if (numAnswersForQuestion > 2) {
            $(this).parents('p').remove();
            numAnswersForQuestion--;
        }
        return false;
    });

    //this is where we add a new slide with the ability to add new questions
    $('#addNewPollSlide').click(function() {

        addNewSlide('poll');

        return false;
    });

    //this is where we add a new slide with the ability to add new questions
    $('#addNewQuizSlide').click(function() {

        addNewSlide('quiz');

        return false;
    });

    //this is where we add a new slide with the ability to add new questions
    $('#addNewPredictionSlide').click(function() {

        addNewSlide('prediction');
        return false;
    });

    function editerPrep() {
        //the following few lines of code are there to make sure it all works when we are editing duels
        numSlides = $("#slideContainer > div").length;

        //if the lenght of the slide array is different to the numSlides then we need to fix that.
        if (numSlides !== slidesArray.length) {
            for (var x = 1; x <= numSlides; x++) {
                slidesArray[x - 1] = x;
                //presume that there are at least two answers attached to each question slide 
//                slidesArray[x - 1][0] = [];
//                slidesArray[x - 1][1] = [];
            }
        }
//        console.log(slidesArray);
        //count the number of slide answers per slide and update the slidesArray to reflect this
//        for (var slideTracker = 1; slideTracker <= numSlides; slideTracker++) {

//            numAnswersForQuestion = $("#answersToQuestion" + String(slideTracker) + " > p").length;
            //if the inner array lenght of the slide array is different to the numAnswers then we need to fix that.
//            if (numAnswersForQuestion !== slidesArray[slideTracker].length) {
//                for (var x = 1; x <= numAnswersForQuestion; x++) {
//                    slidesArray[slideTracker][x - 1] = x;
//                }
//            }
//        }
//        console.log(slidesArray);
    }

    //function to add a new slide with parameters based on wether it is a poll or quiz
    function addNewSlide(type) {
        editerPrep();

        if (numSlides < 5) {
            //hide the previous slide first
            $("#slide" + String(currentSlide)).hide();

            //Add the next slide number for the answers
            numSlides++;
            currentATQ = currentATQ.substring(0, currentATQ.length - 1) + String(numSlides);

            //then add the new slide in place of the previous one
            $('<div class="slide" id="slide' + String(numSlides) + '">' +
                    '<span id="removeSlide" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>' +
                    '<div class="form-group"><div class="col-md-4 control-label"><label for="question">Question</label></div>' +
                    '<div class="col-md-6"><input class="form-control" placeholder="Question" name="question[' + String(numSlides - 1) + ']" type="text" id="question"></div>' +
                    '</div>'+
                    '<span id="' + (type === 'quiz' ? 'addAnswerWithCorrect' : 'addAnswer') + (type === 'prediction' ? 'WithOdds' : '') + '" class="glyphicon glyphicon-plus" aria-hidden="true"></span><div id="answersToQuestion' + String(numSlides) + '"><p><label for="answers">' +
                    '<input type="text" id="answer" size="20" name="answer[' + String(numSlides - 1) + '][0]" value="" placeholder="Answer to question" /></label>' + (type === 'prediction' ? '<label for="probability[' + String(numSlides - 1) + '][0]"><input type="text" id="answer" size="10" name="probability[' + String(numSlides - 1) + '][0]" value="" placeholder="Probablity %" required=""></label>' : '') + '</p>' +
                    (type === 'quiz' ? '<div class="answer-radio"> <input type="radio" name="rightOrWrong[' + String(numSlides - 1) + ']" id="rightOrWrong[' + String(numSlides - 1) + '][0]" class="make-inline-radio" value="0" checked=""><label for="rightOrWrong[' + String(numSlides - 1) + '][0]"> Incorrect</label></div>' : '') +
                    '<p><label for="answers"><input type="text" id="answer" size="20" name="answer[' + String(numSlides - 1) + '][1]" value="" placeholder="Answer to question" /></label>' + (type === 'prediction' ? '<label for="probability[' + String(numSlides - 1) + '][1]"><input type="text" id="answer" size="10" name="probability[' + String(numSlides - 1) + '][1]" value="" placeholder="Probablity %" required=""></label>' : '') + '</p>' +
                    (type === 'quiz' ? '<div class="answer-radio"> <input type="radio" name="rightOrWrong[' + String(numSlides - 1) + ']" id="rightOrWrong[' + String(numSlides - 1) + '][1]" class="make-inline-radio" value="0" checked=""><label for="rightOrWrong[' + String(numSlides - 1) + '][1]"> Correct</label></div>' : '') +
                    '</div></div>').appendTo('#slideContainer');

//to add back in later with the intoduction of tags
//<div class="form-group"><div class="col-md-4 control-label"><label for="tag">Tags</label></div></div>
//<div class="col-md-6"><input class="form-control ui-autocomplete-input tags-input"' +
//                    'placeholder="Question Tags" name="questionTags[' + String(numSlides - 1) + ']" type="text" autocomplete="off"></div>
            //Add the new slide thumbnail
            $('<div class="slideThumbnail" id="slideThumbnail' + String(numSlides) + '">Slide ' + String(numSlides) + '</div>').appendTo($('#slideThumbnails'));


            //update the current slide number
            currentSlide = numSlides;
            slidesArray.push(currentSlide);
            
            //update the image to be the same at the ones before it
            updateBackgrounds();
        }
    }


    //To switch between question slides
    $(document).on('click', ".slideThumbnail", function(event) {
        var domElement = $(event.target);
        switchToSlideNum = domElement.text().substring(6, currentATQ.length - 1);
        switchToSlide(currentSlide, switchToSlideNum);

    });

    //to get the datetimepicker initialized on the quiz creation form
    jQuery('#datetimepicker').datetimepicker();

    //Function to help with switching between slides
    function switchToSlide(theCurrentSlide, slideToSwitchTo) {

        $("#slide" + String(theCurrentSlide)).hide();
        $("#slide" + String(slideToSwitchTo)).show();
        currentATQ = currentATQ.substring(0, currentATQ.length - 1) + String(slideToSwitchTo);

        currentSlide = slideToSwitchTo;
    }

    //when removing a slide by clicking the x button do the following
    $(document).on('click', '#removeSlide', function()
    {
        editerPrep();
        if (numSlides > 1) {

            //remove the slide from the slideArray
            slidesArray.splice(slidesArray.indexOf(parseInt(currentSlide)), 1);

            //remove the slide thumbnail
            var slideRemoved = currentSlide;
            $('#slideThumbnail' + String(currentSlide)).remove();

            //rename all slide thumbnails to the right of the one removed
            // eg. [1,2,3] with 2 removed becomes [1,3] then [1,2]  
            // (-1 off each of them to the right of the one removed)
            var numSlideToUpdate = numSlides - slideRemoved;
            var arrayPositionToUpdate = parseInt(slideRemoved) - 1;
            if (numSlideToUpdate > 0) {
                for (i = 1; i <= numSlideToUpdate; i++) {
                    var slideToUpdate = parseInt(slideRemoved) + parseInt(i);
                    var updatedText = parseInt(slideRemoved) + i - 1;
                    $('#slideThumbnail' + String(slideToUpdate)).text('Slide ' + String(updatedText));
                    $('#slideThumbnail' + String(slideToUpdate)).attr("id", 'slideThumbnail' + String(updatedText));

                    //Make sure all slide numbers in the slidesArray are minus 1 past the one that was removed

                    slidesArray[arrayPositionToUpdate] = updatedText;
                    arrayPositionToUpdate++;

                    //Make the sure the slide ID's are updated too like the slideThumbnails and the slidesArray
                    $('#slide' + String(slideToUpdate)).attr("id", 'slide' + String(updatedText));
                }
            }
            //remove parent div
            $(this).parent('div').remove();

            //switch to another slide 
            switchToSlide(currentSlide, slidesArray[slidesArray.length - 1]);

            numSlides--;
        }

        return false;
    });


    //Dropzone.js Options - Upload an image via AJAX.
    var imageWidth = 640, imageHeight = 420;
    Dropzone.options.myDropzone = {
        uploadMultiple: false,
        maxFilesize: 10, // MB
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: false,
        maxFiles: 100,
        dictDefaultMessage: '',
        init: function() {
            this.on("addedfile", function(file) {
                $('.upload-spinner').show();
            });
            this.on("thumbnail", function(file, dataUrl) {
                
                $('.dz-image-preview').hide();
                $('.dz-file-preview').hide();
                $('.upload-spinner').hide();
                if (file.width < imageWidth || file.height < imageHeight) {     
                     alert("Image width is: " + file.width + "px and height is " + file.height + "px, image dimentions must be greater than 640 pixels wide and 420 pixels high.");
                }else if(file.size > 4000000){
                     alert("Image size is too big! Image size must be less than 4 megabytes.");          
                }
                else {
                    file.acceptDimensions();
                }
            });
            this.on("success", function(file, res) {
                $('.upload-spinner').hide();
                //check if there is already one there and delete it from storage if so
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
                $('input[name="backgroundUrl"]').val(res.path);
                //must be added to the backgrounds of all the current slides
                updateBackgrounds();
//                numSlides = $("#slideContainer > div").length;
//                for (var x = 1; x <= numSlides; x++) {
//                    $('#slide' + String(x)).css('background-image', 'url(' + res.path + ')');
//                }
            });
        },
        accept: function(file, done) {
            file.acceptDimensions = done;
            file.rejectDimensions = function() { 
                    alert("Image width is: " + file.width + "px and height is " + file.height + "px, image size must be greater than 640 pixels wide and 420 pixels high.");
            };
        }
    };
    if ($('#my-dropzone').length) {
        var myDropzone = new Dropzone("#my-dropzone");
    }

    $('#upload-submit').on('click', function(e) {
        e.preventDefault();
        //trigger file upload select
        $("#my-dropzone").trigger('click');
    });




    function split(val) {
        return val.split(/,\s*/);
    }
    function extractLast(term) {
        return split(term).pop();
    }
    //Gets the drop down for the tags working
    $(".tags-input")
            // don't navigate away from the field on tab when selecting an item
            .bind("keydown", function(event) {
                if (event.keyCode === $.ui.keyCode.TAB &&
                        $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 0,
                source: function(request, response) {
                    // delegate back to autocomplete, but extract the last term
                    response($.ui.autocomplete.filter(
                            availableTags, extractLast(request.term)));
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function(event, ui) {
                    var terms = split(this.value);
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push(ui.item.value);
                    // add placeholder to get the comma-and-space at the end
                    terms.push("");
                    this.value = terms.join(",");
                    return false;
                }
            });



    //Adds the duelId to the iframe url         
    $('.view-code').on('click', function() {
        $("#myModal").modal("show");
        $("#iframeCode").val('<iframe src="' + $("#iframeURL").val() + '/' + $(this).closest('tr').children()[0].textContent + '" width="560" height="315" frameBorder="0" seamless="seamless"></iframe>');
    });
    //Gets the width and height of the iframe to change on drop down select
    $("#widthHeight").on('change', function() {
        var splitWidthHeight = $(this).val().split(' ');
        var splitiFrame = $("#iframeCode").val().split('"');
        splitiFrame[3] = splitWidthHeight[0];
        splitiFrame[5] = splitWidthHeight[1];

        $("#iframeCode").val(splitiFrame.join('"'));
    });


    $('.addPopulatedSlide').on('click', function() {

        var questionId = $(this).closest('tr').children()[0].textContent;
        var question = $(this).closest('tr').children()[2].textContent;
        var questionEndDateTime = $(this).closest('tr').children()[1].textContent; 
        //if the question has an end date then add it to the duel end date. 
        //If there are several questions with end dates choose the nearest one to be the overall end date
        if($('#datetimepicker').val() > questionEndDateTime || $('#datetimepicker').val() == ''){
            $('#datetimepicker').val(questionEndDateTime);
        }

        if (numSlides < 10) {
            //hide the previous slide first
            $("#slide" + String(currentSlide)).hide();
            
            //Add the next slide number for the answers
            numSlides++;
            currentATQ = currentATQ.substring(0, currentATQ.length - 1) + String(numSlides - 1);

            //then add the new slide in place of the previous one
            $('<div class="slide" id="slide' + String(numSlides - 1) + '" style="background-image: url('+serverUrl+'/img/system/cc-background.jpg)">' +
                    '<input class="form-control" type="hidden" value="' + String(questionId) + '" name="questions[' + String(numSlides - 1) + ']" ' +
                    '<span id="removeSlide" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>' +
                    '<div class="form-group"><div class="col-md-4 control-label"><label for="question">Question</label></div>' +
                    '<div class="col-md-6"><input class="form-control" placeholder="Question" value="' + question + '" name="question[' + String(numSlides - 1) + ']" type="text" id="question"></div>' +
                    '</div>' +
                    '<div id="answersToQuestion' + String(numSlides - 1) + '"></div>' +                
                    '</div></div>').appendTo('#slideContainer');

            //Add the new slide thumbnail
            $('<div class="slideThumbnail" id="slideThumbnail' + String(numSlides - 1) + '">Slide ' + String(numSlides - 1) + '</div>').appendTo($('#slideThumbnails'));

            //update the current slide number
            currentSlide = numSlides - 1;
            slidesArray.push(currentSlide);
 

            $(".answersToQuestion" + String(questionId)).each(function(index, element) {
                addAnswer($(this).closest('tr').children()[2].textContent, true, 'normal');
            });
        }



        return false;
    });

    //toggle used to see all the answers to a question
    $(".question").click(function() {
        var classToOpen = $(this).closest('tr').children()[0].textContent;
        $(".answersToQuestion" + String(classToOpen)).toggle("fast", function() {
        });
    });

    //Quiz correct/incorrect changing of radio button labels
    $(document).on('mousedown', '.answer-radio :radio', function() {

        console.log();
//        console.log($(this).attr('id'));
        //change the lable of the un selected radio button to say incorrect
        var prevLabel = $("label[for='" + $($('input[type=radio]:checked').get(currentSlide - 1)).attr('id') + "']");
        prevLabel.text('Incorrect');
    }).on("mouseup", '.answer-radio :radio', function() {
        //change the lable of the radio button which has been clicked to say correct.
        var label = $("label[for='" + $(this).attr('id') + "']");
        label.text('Correct');
    });

    //next and previous in the publicly presented duels
    $(".q-a-background").click(function() {
        //count the amount of questions left in the duel
        var numQuestionsToAnswer = $(".content > div").length;
        var duelId = $("#duelId");
        var questionId = $(this).data('questionid');
        var answerId = $(this).data('answerid');
//        console.log("QID: " + questionId + "  Answer ID: " + answerId);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
//        //Send the results of the duel so far to the back end to be put in the db
        var duelId = $("#duelId");
        var questionId = $(this).data('questionid');
        var answerId = $(this).data('answerid');
        var isLastQuestion = false;
        var divToRemove = ".slide-wrapper" + String($(this).data('slidenumber'));
        var nextSlideToShow = $(this).data('slidenumber') + 1;

        $.ajax({
            url: serverUrl + "/public/submit-answer",
            data: [
                {name: "questionId", value: questionId},
                {name: "duelId", value: duelId.val()},
                {name: "answerId", value: answerId},
                {name: "lastQuestion", value: isLastQuestion}
            ],
            method: "POST",
            dataType: "json",
            complete: function() {
                //Hide the current side by removing it from the DOM
                $(divToRemove).remove();
                //if there are no more slides to show show the results 
                if ($(".content > div").length === 0) {
                    window.location.replace(serverUrl + "/public/show-duel-results/" + duelId.val());
                }
                //show the next slide in the list 
                $(".slide-wrapper" + String(nextSlideToShow)).show();
            }
        });

    });
    
    //clicking on the next button in the duel results area 
    $(".next-right-arrow").click(function() {
        $(this).parent().parent().parent().next().show();
        $(this).parent().parent().parent().hide();
    });
    
    //clicking on the next button in the duel results area 
    $(".next-left-arrow").click(function() {
        $(this).parent().parent().parent().prev().show();
        $(this).parent().parent().parent().hide();
    });
    
});

function updateBackgrounds() {
    var imageUrl = $('input[name="backgroundUrl"]').val();
    
    //must be added to the backgrounds of all the current slides 
    var numSlides = $("#slideContainer > div").length;
    //replacing spaces in image urls
    imageUrl = imageUrl.replace(/ /g, '%20');
    
    for (var x = 1; x <= numSlides; x++) {
        $('#slide' + String(x)).css('background-image', 'url(' + imageUrl + ')');
    }

    //replace the image name for s3 so we can delete old backgrounds
    $('input[name="backgroundImageName"]').val(imageUrl);
} 

//we want to manually init the dropzone.
Dropzone.autoDiscover = false;

//# sourceMappingURL=all.js.map