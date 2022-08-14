var Player = function(playlist) {
	this.playlist = playlist;
	this.index = 0;
	this.vol = mvol;
	clearInterval(buffer);
	$('.buffering').remove();
	$('.musicControls').removeClass('overlay');
},skipping=true;
Player.prototype = {
  /**
   * Play a song in the playlist.
   * @param  {Number} index Index of the song in the playlist (leave empty to play the first or current).
   */
  play: function(index) {
    var self = this;
    var sound;

    index = typeof index === 'number' ? index : self.index;
    var data = self.playlist[index];

    // If we already loaded this track, use the current one.
    // Otherwise, setup and load a new Howl.
    if (data.howl) {
      sound = data.howl;
    } else {
      sound = data.howl = new Howl({
        src: ['./sound/'+SFXpackage+'/' + data.file + '.mp3'],
		preload: true,
		volume: 0.8, 
		onplay: function() {
          // Start upating the progress of the track.
			requestAnimationFrame(self.step.bind(self));
			skipping=true;
        },
        onend: function() {
          self.skip('next');
        },
        onseek: function() {
          // Start upating the progress of the track.
          requestAnimationFrame(self.step.bind(self));
        },
		onplayerror: function() {
			sound.once('unlock', function() {
				sound.play();
			});
		}
      });
    }

    // Begin playing the sound.
	sound.volume(this.vol);
    sound.play();

    // Keep track of the index we are currently playing.
    self.index = index;
  },

  /**
   * Pause the currently playing track.
   */
  pause: function() {
    var self = this;

    // Get the Howl we want to manipulate.
    var sound = self.playlist[self.index].howl;

    // Puase the sound.
    sound.pause();
  },

  /**
   * Skip to the next or previous track.
   * @param  {String} direction 'next' or 'prev'.
   */
  skip: function(direction) {
	if (!skipping)
		return;
	skipping=false;
    var self = this;
	
    // Get the next track based on the direction of the track.
    var index = 0;
    if (direction === 'prev') {
      index = self.index - 1;
      if (index < 0) {
        index = self.playlist.length - 1;
      }
    } else {
      index = self.index + 1;
      if (index >= self.playlist.length) {
        index = 0;
      }
    }
	self.skipTo(index);
  },

  /**
   * Skip to a specific track based on its playlist index.
   * @param  {Number} index Index in the playlist.
   */
  skipTo: function(index) {
    var self = this;
	this.vol = self.playlist[self.index].howl.volume();
	
	
	self.playlist.forEach(function(index, chunk) {
		if (index.howl) 
			index.howl.pause()	
	});

    // Play the new track.
    self.play(index);
  },

  /**
   * Set the volume and update the volume slider display.
   * @param  {Number} val Volume between 0 and 1.
   */
  volume: function(val) {
    var self = this;
    // Update the global volume (affecting all Howls).
	var sound = self.playlist[self.index].howl;
	if(val === undefined)
		return sound.volume();
	else{
		sound.volume(val);
	}
  },

  /**
   * Seek to a new position in the currently playing track.
   * @param  {Number} per Percentage through the song to skip.
   */
  seek: function(per) {
    var self = this;

    // Get the Howl we want to manipulate.
    var sound = self.playlist[self.index].howl;

    // Convert the percent into a seek position.
    if (sound.playing()) {
      sound.seek(sound.duration() * per);
    }
  },

  /**
   * The step called within requestAnimationFrame to update the playback position.
   */
  step: function() {
    var self = this;

    // Get the Howl we want to manipulate.
    var sound = self.playlist[self.index].howl;

    // Determine our current seek position.
    var seek = sound.seek() || 0;
    // If the sound is still playing, continue stepping.
    if (sound.playing()) {
      requestAnimationFrame(self.step.bind(self));
    }
  }
};