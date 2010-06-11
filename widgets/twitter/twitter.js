new TWTR.Widget({
  profile: true,
  id: 'twtr-profile-widget',
  loop: true,
  width: 210,
  height: 300,
  theme: {
    shell: {
      background: '#a50000',
      color: '#f6f3ec'
    },
    tweets: {
      background: '#f6f3ec',
      color: '#422c1a',
      links: '#a50000'
    }
  }
}).render().setProfile('kamusiproject').start();
