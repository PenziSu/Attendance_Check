from line import sys,LineClient,LineGroup,LineContact

try:
    client = LineClient(authToken="DPg6LCpab578tZrNzCvf.GecCs48wBQyh1HX4GyKvRW.9wWXZUysh3o02y0J6H2N8tge/0AhOBf6C5vIugtL6YQ=")
except:
    print "Login Failed"

while True:
    #print client.contacts
    client.contacts[4].sendMessage(sys.argv[1])
    #print "Message sent."
    break
