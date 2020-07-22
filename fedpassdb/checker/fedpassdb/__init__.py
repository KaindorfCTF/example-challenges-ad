#!/usr/bin/python3

from ctf_gameserver.checker import BaseChecker, OK, NOTWORKING, NOTFOUND
import requests
import hashlib

class FedPassDbChecker(BaseChecker):
    def __init__(self, tick, team, service, ip):
        super().__init__(tick, team, service, ip)
        self.ip = ip
        self.port = 80
        self.timeout = 10
        self.baseurl = "http://" + self.ip + ":" + str(self.port) + "/"
    
    def register_user(self, user, pw):
        resp = requests.post(self.baseurl + 'index.php?page=register',
                data={"username": user, "password" : pw, "action": "register"},
                timeout=self.timeout)
        if not b"Registration successful!" in resp.content:
            return False
        return True

    def generate_credentials(self, tick):
        flag = self.get_flag(self._tick).encode('latin-1')
        h = hashlib.sha256(flag).hexdigest()
        user = 'bot_'+h[0:16]
        pw = h[16:32]
        return flag, user, pw

    def check_service(self):
        try:
            s = requests.Session()
            resp = s.get(self.baseurl+'Login', timeout=self.timeout)
            resp = s.get(self.baseurl+'Register', timeout=self.timeout)
            resp = s.get(self.baseurl+'Status', timeout=self.timeout)
            return OK
        except Exception as e:
            self._logger.debug('check_service NOTWORKING {}'.format(e))
            return NOTWORKING

    def place_flag(self):
        flag, user, pw = self.generate_credentials(self._tick)
        self._logger.debug('place_flag for tick {} flag {} user {} pw {}'.format(self._tick, flag, user, pw))
        try:
            if not self.register_user(user, pw):
                self._logger.debug('could not register user')
                return NOTWORKING
            s = requests.Session()
            resp = s.post(self.baseurl+'index.php',
                    data={"username": user, "password" : pw, "action": "login"},
                    timeout=self.timeout)
            resp = s.post(self.baseurl+'index.php',
                    data={"server": user, "username": user, "password" : flag, "action": "addsniff"},
                    timeout=self.timeout)
            if not b'<td>' + bytes(user, 'utf-8') + b'</td>' in resp.content:
                self._logger.debug('could not find server entry in list')
                return NOTWORKING
            if not b'<td>' + flag + b'</td>' in resp.content:
                self._logger.debug('flag not found')
                return NOTFOUND
            return OK
        except Exception as e:
            self._logger.debug('place_flag NOTWORKING {}'.format(e))
            return NOTWORKING
        self._logger.debug('place_flag OK')
        return OK

    def check_flag(self, tick):
        flag, user, pw = self.generate_credentials(self._tick)
        self._logger.debug('check_flag for tick {} flag {} user {} pw {}'.format(self._tick, flag, user, pw))
        try:
            s = requests.Session()
            resp = s.post(self.baseurl+'index.php',
                    data={"username": user, "password" : pw, "action": "login"},
                    timeout=self.timeout)
            if not b'<td>' + bytes(user, 'utf-8') + b'</td>' in resp.content:
                self._logger.debug('could not find server entry in list')
                return NOTWORKING
            if not b'<td>' + flag + b'</td>' in resp.content:
                self._logger.debug('flag not found')
                return NOTFOUND
            return OK
        except Exception as e:
            self._logger.debug('check_flag NOTWORKING {}'.format(e))
            return NOTWORKING
        return NOTFOUND
        self._logger.debug('check_flag NOTFOUND')

