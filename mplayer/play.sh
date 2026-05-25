#!/bin/bash

echo Trying to play $* >&2

mplayer -quiet -noconsolecontrols -fs -slave -input file=/tmp/mplayer-fifo msglevel "$1" </dev/null  >/tmp/mplayer.out &

echo "Playing $1!" >&2

