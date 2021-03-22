# Example Challenges for CTF-Gameserver setup
This repository will be processed by an ansible script.
The directory structure has to be:

```
chal1
    checker
        challenge.py
        requirements.txt
    vuln ... an ansible role
        tasks
            main.yml
    exploit
        xxx ... put your exploit here for manual testing
chal2
    ...
```

## Testing your checker

You must install the CTF-Gameserver locally:

```
git clone --branch kdctf https://github.com/KaindorfCTF/ctf-gameserver.git
```

Now create a virtualenv and install it (assuming you are in the `checker` directory):

```
virtualenv -p /usr/bin/python3 ENV
source ./ENV/bin/activate
pip install /path/to/ctf-gameserver/
pip install -r requirements.txt
```

Now you can test your checker using:

```
python checker.py <ip> <team-net-no> <tick>
```

With parameters:

  - `ip`: IP of your target service
  - `team-net-no`: irrelevant (use `1` for instance)
  - `tick`: tick number (use incrementing numbers starting with `0`)