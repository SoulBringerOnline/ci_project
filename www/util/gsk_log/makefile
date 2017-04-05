DIRS_ALL= src
all:
		@for dir in $(DIRS_ALL); do (echo -e "\033[40;32;4mMAKE \033[0m \033[40;31;4m $$dir \033[0m \033[40;32m \033[0m") ;make -C $$dir; echo; done
clean:
		@for dir in $(DIRS_ALL); do (echo -e "\033[40;32;4mMAKE CLEAN \033[0m \033[40;31;4m $$dir \033[0m \033[40;32m \033[0m") ;make clean -C $$dir; echo; done

cleanall:
		@for dir in $(DIRS_ALL); do (echo -e "\033[40;32;4mMAKE CLEAN \033[0m \033[40;31;4m $$dir \033[0m \033[40;32m \033[0m") ;make cleanall -C $$dir; echo; done
