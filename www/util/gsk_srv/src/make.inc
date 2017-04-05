#-------------------------------------------------------------------------------
all : $(LOCAL_OBJ) $(TARGET) $(TARGETS)
	rm -f *.o
	@if [ ! -z "$(filter-out %.so %.a,$(TARGET))" ]; then  (echo -e "\033[40;35;4m[mv $(filter-out %.so %.a,$(TARGET)) $(BIN_PATH)] \033[0m")   ; mv $(filter-out %.so %.a,$(TARGET)) $(BIN_PATH) ; fi
	@if [ ! -z "$(filter-out %.so %.a,$(TARGETS))" ]; then  (echo -e "\033[40;35;4m[mv $(filter-out %.so %.a,$(TARGETS)) $(BIN_PATH)] \033[0m") ; mv $(filter-out %.so %.a,$(TARGETS)) $(BIN_PATH) ; fi

#-------------------------------------------------------------------------------

$(filter %.a,$(TARGET)) : $(LOCAL_OBJ) $(REMOTE_OBJ)
	$(QUIET)ar r $@ $(LOCAL_OBJ)

$(filter %.so,$(TARGET)) : $(LOCAL_OBJ) $(REMOTE_OBJ)
	$(QUIET)$(CC) -shared -o $@ $(LOCAL_OBJ)

$(filter-out %.so %.a,$(TARGET)) : $(LOCAL_OBJ) $(REMOTE_OBJ)
	$(QUIET)$(CXX) $(CFLAGS) -o $@ $^ $(INC) $(LIB)

$(filter-out %.so %.a %.y,$(TARGETS)) : % : %.o
	$(QUIET)$(CXX) $(CFLAGS) -o $@ $^ $(INC) $(LIB)

#----------------------------------------------------------------------------------

tar: $(TARGET) $(CONFIG)
	@if [ -d $(TARGET)_tmp_dir ]; then \
		echo "dir has exist:$(TARGET)_tmp_dir, abort."; \
		exit 1; \
	else \
		mkdir $(TARGET)_tmp_dir $(TARGET)_tmp_dir/$(TARGET);\
		cp $(TARGET) $(CONFIG) $(TARGET)_tmp_dir/$(TARGET)/; \
		cd $(TARGET)_tmp_dir; tar cvfz $(TARGET).tgz $(TARGET)/; cd ..; \
		if [ -f "$(TARGET).tgz" ]; then \
			mv -vf $(TARGET).tgz $(TARGET).`date +%Y%m%d%H%M%S`.tgz; \
		fi; \
		mv $(TARGET)_tmp_dir/$(TARGET).tgz ./; \
        
		rm -rf $(TARGET)_tmp_dir; \
		echo "tar cvfz $(TARGET).tgz ..."; \
	fi
#----------------------------------------------------------------------------------
clean:
	rm -vf $(LOCAL_OBJ) $(TARGET) $(TARGETS) ${CLEANFILE} .*.d.tmp gmon.out
cleanall:
	rm -vf $(LOCAL_OBJ) $(TARGET) $(TARGETS) $(DEP_FILE) ${CLEANFILE} *.o .*.d.tmp .*.d gmon.out core.* *.log
-include $(DEP_FILE)

#-------------------------------------------------------------------------------
.%.pb.d: %.pb.cc
#	@echo "update $@ ..."; \
#    echo -n $< | sed s/\.cc/\.o:/ > $@; \
	$(QUIET)$(CC) $(INC) -MM $< | sed '1s/.*.://' >> $@;

%.pb.o: %.pb.cc
	$(QUIET)$(CXX) $(CFLAGS) $(INC) -o $@ -c $<

.%.d: %.cpp
#	@echo "update $@ ..."; \
#    echo -n $< | sed s/\.cpp/\.o:/ > $@; \
	$(QUIET)$(CC) $(INC) -MM $< | sed '1s/.*.://' >> $@;

%.o: %.cpp
	$(QUIET)$(CXX) $(CFLAGS) $(INC) -o $@ -c $<

.%.d: %.c
#	@echo "update $@ ..."; \
#	echo -n $< | sed s/\.c/\.o:/ > $@; \
	$(QUIET)$(CC) $(INC) -MM $< | sed '1s/.*.://' >> $@;

%.o: %.c
	$(QUIET)$(CC) $(CFLAGS) $(INC) -o $@ -c $<

#----------------------------------------------------------------------------------
