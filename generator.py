from pystrich.datamatrix import DataMatrixEncoder
#import subprocess
import os
import sys

#import binascii
#import secrets
import random

# clean folder 
os.system("rm sample_barcodes/*.png")

f = open("generated_errors_python.txt", "w+")
f.write("")
f.close()

for x in range(1, 3):

    # hex_string = str(binascii.b2a_hex(os.urandom(15)))
    # hex_string =  str(secrets.token_hex(84).upper())
    hex_sinput = str(random.randint(10, 33)) + str(random.randint(10, 33)) + str(
        random.randint(10, 33)) + str(random.randint(10, 33)) + str(random.randint(10, 33))
    hex_sinput = hex_sinput.strip().replace(' ', '')
    hex_sinput = hex_sinput + hex_sinput + "00000000000000000000000000000000000000000000000000000000"
    # sys.exit()

    # for x in arrayHex:
    # print(x)

    # sys.exit()
    encoder = DataMatrixEncoder(bytes.fromhex(hex_sinput).decode('iso-8859-1'))  # iso-8859-15
    # encoder = DataMatrixEncoder(hex_string) # iso-8859-15
    encoder.save("sample_barcodes/datamatrix_test_" + str(x) + ".png")
    # print(encoder.get_ascii())

    bashCommand = "dmtxread -v -N 1  sample_barcodes/datamatrix_test_" + str(x) + ".png | tail -n 1 | od -An -vtx1"
    # os.system(bashCommand)
    hex_string = os.popen(bashCommand).read()
    hex_output = hex_string.strip().replace(' ', '')
    hex_output = hex_output.replace("\n", '')

    if (hex_sinput == hex_output):
        print("[ PYTHON DMTXREAD DECODED BIN2HEX REVERS OK] ")
        print("[ SINPUT HEX: " + hex_sinput + " ]")
        print("[ OUTPUT HEX: " + hex_output + " ]")
    else:
        print("[ PYTHON DMTXREAD DECODED BIN2HEX REVERS ERROR] ")
        print("[ SINPUT HEX: " + hex_sinput + " ]")
        print("[ OUTPUT HEX: " + hex_output + " ]")

        f = open("generated_errors_python.txt", "w+")
        f.write("DECODED BIN2HEX REVERS ERROR!!!!  %d\r\n")
        f.write("[ SINPUT HEX: " + hex_sinput + " ] %d\r\n")
        f.write("[ OUTPUT HEX: " + hex_output + " ] %d\r\n")
        f.close()
