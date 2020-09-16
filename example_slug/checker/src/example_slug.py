#!/usr/bin/env python3

import logging
import socket

from ctf_gameserver import checkerlib


class ExampleChecker(checkerlib.BaseChecker):

    def place_flag(self, tick):
        return checkerlib.CheckResult.OK

    def check_service(self):
        return checkerlib.CheckResult.OK

    def check_flag(self, tick):
        return checkerlib.CheckResult.OK


def connect(ip):

    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.settimeout(5)
    sock.connect((ip, 9999))
    return sock


def recv_line(conn):

    received = b''
    while not received.endswith(b'\n'):
        new = conn.recv(1024)
        if len(new) == 0:
            if not received.endswith(b'\n'):
                raise EOFError('Unexpected EOF')
            break
        received += new
    return received.decode('utf-8').rstrip()


if __name__ == '__main__':

    checkerlib.run_check(ExampleChecker)
