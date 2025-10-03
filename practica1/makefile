# Define the targets
PROGRAM1 = server.c
PROGRAM2 = client.c

# Define the executable names
EXECUTABLE1 = server
EXECUTABLE2 = client

# Compiler and flags
CC = gcc
CFLAGS = -Wall -g  # -Wall enables all warnings, -g enables debugging information

# Default target
all: $(EXECUTABLE1) $(EXECUTABLE2)

# Rule for compiling program1
$(EXECUTABLE1): $(PROGRAM1)
	$(CC) $(CFLAGS) $(PROGRAM1) -o $(EXECUTABLE1)

# Rule for compiling program2 (depends on program1)
$(EXECUTABLE2): $(EXECUTABLE1)
	$(CC) $(CFLAGS) $(PROGRAM2) -o $(EXECUTABLE2)

# Clean rule
clean:
	rm -f $(EXECUTABLE1) $(EXECUTABLE2)
