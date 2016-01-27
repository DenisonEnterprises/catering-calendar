import json
import urllib

key = "AIzaSyBBzQwDZdAbTQ5mtDmxUoSBDd6japEiaCc"
calendarId = "denison.edu_5ssoueqdeb05s9boa80nlrnru4@group.calendar.google.com"
url = "https://www.googleapis.com/calendar/v3/calendars/" + calendarId + "/events?key=" + key

def get_json():
    response = urllib.urlopen(url)
    data = json.loads(response.read())
    print(json.dumps(data, sort_keys=True, indent=4, separators=(',', ': ')))

if __name__ == '__main__':
    get_json()