// Imports
import $ from 'jquery';
import Routing from 'fos-router';
import axios from "axios";

// Setup
const CONTAINER_BUSY_STATE = 'busy';
const CONTAINER_PAUSED_STATE = 'paused';
var activeDomet = undefined;
var buttonsound = new Audio('/button-sfx.mp3');
var alarmsound = new Audio('/alarm-sfx.mp3');
alarmsound.volume = 0.6;

// Show or hide the counter container
$(".counter-parent").on({
    mouseenter: function () {
        $(this).find('.counter-container').fadeIn(100);
    },
    mouseleave: function () {
        if (isCounterContainerPaused($(this).find('.counter-container'))) {
            $(this).find('.counter-container').fadeOut('slow');
        }
    }
});

function isCounterContainerPaused(container)
{
    if (container.data('state') == CONTAINER_PAUSED_STATE) {
        return true;
    }
    return false;
}

// Start the functionality of starting or stopping a domet
$(".counter-parent").on('click', function() {
    handleContainerOrActionButtonClick($(this).find('.counter-container'));
});
$(".counter-play-button").on('click', function(e) {
    handleContainerOrActionButtonClick($(this).closest('.counter-container'));
    e.stopPropagation(); // Prevent propagating to ancestor!
});

function handleContainerOrActionButtonClick(container)
{
    buttonsound.play();
    if (isCounterContainerPaused(container)) {
        startDomet(container);
    } else {
        stopDomet(container);
    }
    cleanup();
}

function cleanup()
{
    if (activeDomet === undefined) {
        $(document).find('.counter-parent').each(function () {
            activateParentContainer($(this));
        })
    }
}

function completeDomet(container)
{
    alarmsound.play();
    container.data('task-time-remaining', container.data('default-task-time'));
    stopDomet(container);
    container.find('.counter-icon').removeClass('fa-pause').removeClass('fa-play').addClass('fa-rotate');
    container.closest('.counter-parent').find('.progress').css("width", "0%");
    cleanup();
}

function startDomet(container)
{
    // Stop all other Domets
    var containerTaskId = container.data('task-id');
    $('.counter-container').each(function () {
        if (containerTaskId !== $(this).data('task-id')) {
            deactivateParentContainer($(this));
            if (! isCounterContainerPaused($(this))) {
                stopDomet($(this));
                $(this).trigger('mouseleave');
            }
        }
    })
    // Do an ajax call to start a Domet
    axios.get(Routing.generate('domet_add', {'task': containerTaskId}))
        .then(function (response) {
            console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
    // Change the state to busy
    changeContainerState(container, CONTAINER_BUSY_STATE);
    // Change parent
    activateParentContainer(container);
    // Start the timer
    startCounter(container);
    activeDomet = container;
}

function stopDomet(container)
{
    // Do an ajax call?
    console.log('calling to stop domet for task ' + container.data('task-id'));
    // Change the state
    changeContainerState(container, CONTAINER_PAUSED_STATE);
    // Change parent
    deactivateParentContainer(container);
    activeDomet = undefined;
}

function changeContainerState(container, state)
{
    container.data('state', state);
    changeCounterIcon(container, state);
}

function toggleContainerParentIsActive(container)
{
    if (container.closest('.counter-parent').hasClass('domet-inactive')) {
        deactivateParentContainer(container);
    } else {
        activateParentContainer(container);
    }
}

function deactivateParentContainer(container)
{
    container.closest('.counter-parent').addClass('domet-inactive');
}

function activateParentContainer(container)
{
    container.closest('.counter-parent').removeClass('domet-inactive');
}

function changeCounterIcon(container, state)
{
    if (state === CONTAINER_PAUSED_STATE) { // Pause the container
        container.find('.counter-icon').removeClass('fa-pause').addClass('fa-play');
    } else if (state === CONTAINER_BUSY_STATE) {
        container.find('.counter-icon').removeClass('fa-play').addClass('fa-pause');
    }
}


function startCounter(container)
{
    var destination = container.find('.counter-digits');
    var timeRemaining = container.data('task-time-remaining');
    var defaultTaskTime = container.data('default-task-time');
    if (timeRemaining === undefined) {
        timeRemaining = 30 * 60 * 1000;
    }
    // check validity of time-remaining
    var countdown = new Date(0);
    countdown.setMilliseconds(timeRemaining);
    let timer = setInterval(function () {
        if (! isCounterContainerPaused(container) && countdown >= 0) {
            destination.html(countdown.toISOString().substring(14, 22).replace(".", ":"));
            countdown.setMilliseconds(countdown.getMilliseconds() - 10);
            container.data('task-time-remaining', countdown.getTime());
            updateProgressBar(container, countdown.getTime(), defaultTaskTime);
        } else {
            if (! isCounterContainerPaused(container)) {
                completeDomet(container);
            }
            clearInterval(timer);
        }
    }, 10);
}

function updateProgressBar(container, timeRemaining, defaultTime)
{
    var percentage = Math.floor((((defaultTime - timeRemaining) / defaultTime) * 100));
    container.closest('.counter-parent').find('.progress').css("width", percentage+"%");
}