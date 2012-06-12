plg_skroutzeasy.zip:
	zip -r plg_skroutzeasy.zip . -x @.zipignore

.PHONY: clean

clean:
	rm -f plg_skroutzeasy.zip
