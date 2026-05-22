#!/bin/bash

echo Trying to play $* >&2

mplayer -really-quiet -noconsolecontrols -fs -slave -input file=/tmp/mplayer-fifo "$1" </dev/null >/tmp/mplayer.out 2>/dev/null &

echo "Playing $1!" >&2

