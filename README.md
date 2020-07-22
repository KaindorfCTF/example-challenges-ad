# Example Challenges for CTF-Gameserver setup
This repository will be processed by an ansible script.
The directory structure has to be:

```
chal1
    checker
        chal1 ... python module
            __init__.py
        setup.py
        chal1.conf
    vuln ... an ansible role
        tasks
            main.yml
    exploit
        xxx ... put your exploit here for manual testing
chal2
    ...
```
